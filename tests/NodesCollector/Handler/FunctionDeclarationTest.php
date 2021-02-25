<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\FunctionDeclaration;
use DepCheck\NodesCollector\Handlers\NodeCollection;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\UnionType;
use PHPUnit\Framework\TestCase;

final class FunctionDeclarationTest extends TestCase
{
    /** @test */
    public function it_collects_function_name_prams_and_return_types(): void
    {
        $unionType = new UnionType([new Name('p2'), new Name('p3')]);
        $node = new Function_(new Name('fun'), [
            'params'=>[
                new Param('p', null, new Name('p1')),
                new Param('p2', null, $unionType),

            ],
            'returnType' => new Name('r1')
        ]);

        $p1 = $this->buildNode('p1');
        $p2 = $this->buildNode('p2');
        $p3 = $this->buildNode('p3');
        $r1 = $this->buildNode('r1');
        $deps = [
            $this->buildDep($p1, -1, 0, NodeDependency::PARAM),
            $this->buildDep($p2, -1, 0, NodeDependency::PARAM),
            $this->buildDep($p3, -1, 0, NodeDependency::PARAM),
            $this->buildDep($r1, -1, 0, NodeDependency::RETURN),
        ];
        $funcNode = $this->buildNode('fun', $deps);

        $expected = [
            'fun' => $funcNode,
            'p1' => $p1,
            'p2' => $p2,
            'p3' => $p3,
            'r1' => $r1
        ];
        $h = new FunctionDeclaration(new NodeCollection());
        $h->handle($node);
        $this->assertEquals($expected, $h->getNodes());
    }

    protected function buildDep(Node $node, int $line, int $col, int $type): NodeDependency
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
