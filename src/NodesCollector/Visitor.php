<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\NodesCollector\Handlers\ClassDeclaration;
use DepCheck\NodesCollector\Handlers\ClassMethod;
use DepCheck\NodesCollector\Handlers\ClassProperty;
use DepCheck\NodesCollector\Handlers\FunctionCall;
use DepCheck\NodesCollector\Handlers\FunctionDeclaration;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;

final class Visitor extends NodeVisitorAbstract
{
    private array $tokens;
    private FunctionDeclaration $functionDeclarationHandler;
    private FunctionCall $functionCallHandler;

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
        ];
    }

    public function getNodes(): array
    {
        $nodes = [];
        foreach ($this->handlers as $handelr) {
            $nodes[] = $handelr->getNodes();
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
     *      - global constant name node
     *
     *  - function -- done
     *      - declaration  -- done
     *      - call -- done
     *
     *  - class
     *      - declaration -- done
     *
     *      - function params -- done
     *      - function return type -- done
     *      - function docblock
     *      - method params -- done
     *      - method return type -- done
     *      - method docblock
     *
     *      - extends
     *      - method call
     *      - property access
     *      - constant access
     *      - instantiation
     *
     *  - interface
     *      - declaration
     *
     *      - function params -- done
     *      - function return type -- done
     *      - function docblock
     *      - method params -- done
     *      - method return type -- done
     *      - method docblock
     *
     *      - implements
     *      - extends
     *  - trait
     *      - declaration
     *      - use statement
     *
     */
    public function leaveNode(Node $node)
    {
        $type = get_class($node);
        if (isset($this->handlers[$type])) {
            $this->handlers[$type]->handle($node);
        }
    }

}
