<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\NodesCollector\Handlers\ClassConstantHandler;
use DepCheck\NodesCollector\Handlers\ClassRefHandler;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassDeclaration;
use DepCheck\NodesCollector\Handlers\ClassInstantiationHandler;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassMethod;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassProperty;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\InterfaceDeclarationHandler;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\TraitDeclaration;
use DepCheck\NodesCollector\Handlers\FunctionCall;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\FunctionDeclaration;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use DepCheck\NodesCollector\Handlers\GlobalConstantHandler;
use DepCheck\NodesCollector\Handlers\GlobalConstHandler;
use DepCheck\NodesCollector\Handlers\NodeCollection;
use DepCheck\NodesCollector\Handlers\RefHandler;
use DepCheck\NodesCollector\Handlers\TraitUseHandler;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;

final class Visitor extends NodeVisitorAbstract
{
    private array $tokens;

    /** @var AbstractHandler[] */
    private array $handlers = [];

    private NodeCollection $nodes;

    public function __construct()
    {
        $this->nodes = new NodeCollection();
        $classRef = new ClassRefHandler($this->nodes);
        $refHandler = new RefHandler($this->nodes);

        $this->handlers = [
            Node\Stmt\Class_::class => new ClassDeclaration($this->nodes),
            Node\Stmt\ClassMethod::class => new ClassMethod($this->nodes),
            Node\Stmt\Property::class => new ClassProperty($this->nodes),
            Function_::class => new FunctionDeclaration($this->nodes),
            Node\Stmt\Interface_::class => new InterfaceDeclarationHandler($this->nodes),
            Node\Expr\FuncCall::class => $refHandler,
            Node\Expr\New_::class => $refHandler,

            Node\Expr\ConstFetch::class => new GlobalConstHandler($this->nodes),

            Node\Expr\ClassConstFetch::class => $classRef,
            Node\Expr\StaticCall::class => $classRef,
            Node\Expr\StaticPropertyFetch::class => $classRef,

            Node\Stmt\Trait_::class => new TraitDeclaration($this->nodes),
            Node\Stmt\TraitUse::class => new TraitUseHandler($this->nodes)
        ];

    }

    public function getNodes(): array
    {
        return $this->nodes->toArray();
    }

    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }


    /**
     *
     * Node types:
     *  - Class
     *  - Interface
     *  - Trait
     *  - Function
     *  - Global Constant
     *
     *
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
     *      - declaration           -- done
     *
     *      - function params       -- done
     *      - function return type  -- done
     *      - function docblock
     *      - method params         -- done
     *      - method return type    -- done
     *      - method docblock
     *
     *      - implements            -- done
     *      - extends               -- done
     *
     *  - trait
     *      - declaration           -- done
     *      - use statement         -- done
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
