<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\Node;

final class NodeCollection
{
    /**
     * @var array<string,Node> $nodes
     */
    private array $nodes = [];

    public function has(string $id): bool
    {
        return isset($this->nodes[$id]);
    }

    public function get(string $id): Node
    {
        return $this->nodes[$id];
    }

    public function set(Node $node): void
    {
        $this->nodes[$node->id] = $node;
    }

    public function toArray(): array
    {
        return $this->nodes;
    }
}
