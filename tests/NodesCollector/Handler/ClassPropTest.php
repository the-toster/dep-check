<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassProperty;
use DepCheck\NodesCollector\Handlers\NodeCollection;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;

final class ClassPropTest extends TestCase
{
    /** @test */
    public function it_collects_prop_type(): void
    {
        $handler = new ClassProperty(new NodeCollection());
        $prop = new Property(
            0,
            [new PropertyProperty('name')],
            ['parent' => new Name('SomeClass')],
            new Name('t1')
        );

        $propType = $this->buildNode('t1');
        $dep = $this->buildDep($propType, NodeDependency::PROPERTY);
        $expected = [
            'SomeClass' => $this->buildNode('SomeClass', [$dep]),
            't1' => $propType
        ];

        $handler->handle($prop);
        $this->assertEquals($expected, $handler->getNodes());
    }

    protected function buildDep(Node $node, int $type, int $line = -1, int $col = 0): NodeDependency
    {
        return new NodeDependency($node, new NodePosition($line, $col, ''), $type);
    }

    /**
     * @param string $id
     * @param NodeDependency[] $deps
     * @return Node
     */
    private function buildNode($id, $deps = []): Node
    {
        return new Node($id, $deps, new Properties(''));
    }

}
