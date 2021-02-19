<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\NodesCollector\Handlers\ClassConstantHandler;
use DepCheck\NodesCollector\Handlers\ClassDeclaration;
use DepCheck\NodesCollector\Handlers\ClassInstantiationHandler;
use DepCheck\NodesCollector\Handlers\ClassMethod;
use DepCheck\NodesCollector\Handlers\ClassProperty;
use DepCheck\NodesCollector\Handlers\FunctionCall;
use DepCheck\NodesCollector\Handlers\FunctionDeclaration;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use DepCheck\NodesCollector\Handlers\GlobalConstantHandler;
use DepCheck\NodesCollector\Handlers\StaticMethodCallHandler;
use DepCheck\NodesCollector\Handlers\StaticPropertyHandler;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;

final class Visitor extends NodeVisitorAbstract
{
    private array $tokens;

    /** @var AbstractHandler[] */
    private array $handlers = [];

    public function __construct()
    {
        $this->handlers = [
            Node\Stmt\Class_::class => new ClassDeclaration(),
            Node\Stmt\ClassMethod::class => new ClassMethod(),
            Node\Stmt\Property::class => new ClassProperty(),
            Node\Expr\FuncCall::class => new FunctionCall(),
            Function_::class => new FunctionDeclaration(),
            Node\Expr\ConstFetch::class => new GlobalConstantHandler(),
            Node\Expr\ClassConstFetch::class => new ClassConstantHandler(),
            Node\Expr\StaticCall::class => new StaticMethodCallHandler(),
            Node\Expr\StaticPropertyFetch::class => new StaticPropertyHandler(),
            Node\Expr\New_::class => new ClassInstantiationHandler()
        ];
    }

    public function getNodes(): array
    {
        $nodes = [];
        foreach ($this->handlers as $handler) {
            $nodes[] = $handler->getNodes();
        }

        return $this->mergeNodes($nodes);
    }

    /**
     * @param array<CheckNode[]> $nodes
     * @return CheckNode[]
     */
    private function mergeNodes(array $nodes): array
    {
        $r = [];
        foreach ($nodes as $part) {
            foreach ($part as $id => $node) {
                if (isset($r[$id])) {
                    foreach ($node->depends as $dependency) {
                        $r[$id]->depends[] = $dependency;
                    }
                } else {
                    $r[$id] = $node;
                }
            }
        }

        return $r;
    }

    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Collect nodes from AST:
     *  node types & ref sources:
     *  - global constant
     *      - global constant fetch -- done
     *
     *  - function                  -- done
     *      - declaration           -- done
     *      - call                  -- done
     *
     *  - class
     *      - declaration           -- done
     *
     *      - function params       -- done
     *      - function return type  -- done
     *      - function docblock
     *      - method params         -- done
     *      - method return type    -- done
     *      - method docblock
     *
     *      - extends               -- done
     *      - static method call    -- done
     *      - static property access -- done
     *      - method call           -- very problematic
     *      - property access       -- very problematic
     *      - constant access       -- done
     *      - instantiation         -- done
     *
     *  - interface
     *      - declaration
     *
     *      - function params       -- done
     *      - function return type  -- done
     *      - function docblock
     *      - method params         -- done
     *      - method return type    -- done
     *      - method docblock
     *
     *      - implements            -- done
     *      - extends
     *  - trait
     *      - declaration
     *      - use statement
     *
     */
    public function leaveNode(Node $node)
    {
        $type = get_class($node);
//        echo $type."\n";
        if (isset($this->handlers[$type])) {
//            echo get_class($this->handlers[$type])."\n";
            $this->handlers[$type]->handle($node);
        }
    }

}
