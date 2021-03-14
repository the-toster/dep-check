<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use PhpParser\Node;
use PhpParser\NodeVisitor;

final class NameResolutionVisitor implements NodeVisitor
{
    private NodeVisitor\NameResolver $nameResolver;
    private array $aliases = [];

    public function __construct(NodeVisitor\NameResolver $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }


    public function beforeTraverse(array $nodes)
    {
        $this->nameResolver->beforeTraverse($nodes);
    }

    public function enterNode(Node $node)
    {
        $this->nameResolver->enterNode($node);
    }

    public function leaveNode(Node $node)
    {
        $this->nameResolver->leaveNode($node);
    }

    public function afterTraverse(array $nodes)
    {
        $this->nameResolver->afterTraverse($nodes);
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    public function getNamespace(): string
    {
        $ns = $this->nameResolver->getNameContext()->getNamespace();
        return $ns === null ? $ns : $ns->toCodeString();
    }
}
