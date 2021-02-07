<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\NodesCollector\Handlers\FunctionCallHandler;
use DepCheck\NodesCollector\Handlers\FunctionDeclarationHandler;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;

final class Visitor extends NodeVisitorAbstract
{
    private array $tokens;
    private FunctionDeclarationHandler $functionDeclarationHandler;
    private FunctionCallHandler $functionCallHandler;

    /** @var AbstractHandler[] */
    private array $handlers = [];

    public function __construct()
    {
        $this->handlers = [
            Function_::class => new FunctionDeclarationHandler(),
            Node\Expr\FuncCall::class => new FunctionCallHandler()
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
     *  node types:
     *  - class
     *  - trait
     *  - interface
     *  ---- function
     *  - constant
     *
     *  ref types:
     *  - extends
     *  - implements
     *  - use
     *  - use trait
     *  - call method
     *  - call function
     *  - read constant
     *  - param type hint
     *  - property type hint
     *  - return type hint
     *  - docblock type hint
     */
    public function leaveNode(Node $node)
    {
        $type = get_class($node);
        if (isset($this->handlers[$type])) {
            $this->handlers[$type]->handle($node);
        }
    }

}
