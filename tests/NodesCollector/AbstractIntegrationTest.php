<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use PHPUnit\Framework\TestCase;

abstract class AbstractIntegrationTest extends TestCase
{

    protected function buildDep(Node $node, int $line, int $col, int $type): NodeDependency
    {
        return new NodeDependency($node, new NodePosition($line, $col, ''), $type);
    }

    /**
     * @param string $id
     * @param NodeDependency[] $deps
     * @return Node
     */
    protected function buildNode($id, $deps = []): Node
    {
        return new Node($id, $deps, new Properties(''));
    }

    abstract protected function getTestContent(): string;
}
