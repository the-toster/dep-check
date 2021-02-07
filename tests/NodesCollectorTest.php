<?php

declare(strict_types=1);

namespace Tests;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use PHPUnit\Framework\TestCase;

final class NodesCollectorTest extends TestCase
{
    /**
     * @covers \DepCheck\NodesCollector\Visitor
     * @covers \DepCheck\NodesCollector\NodeExtractor
     * @test
     */
    public function it_collects_functions_declarations(): void
    {
        //function declaration can depends by arguments and return type
        //also, it is node itself, something other can depends on it
        $file = new SourceFile('name', $this->getTestContent());
        $nodes = (new NodeExtractor())->extract($file);

        $argNode = $this->buildNode('ArgDep');
        $retNode = $this->buildNode('ReturnDep');
        $funcCall = $this->buildNode('var_dump');
        $deps = [
            $this->buildDep($argNode, 3, 0, NodeDependency::PARAM),
            $this->buildDep($retNode, 3, 0, NodeDependency::RETURN),
            $this->buildDep($funcCall, 5, 0, NodeDependency::CALL),
        ];
        $funcNode = $this->buildNode('test', $deps);

        $this->assertEquals(
            [
                'test' => $funcNode,
                'ArgDep' => $argNode,
                'ReturnDep' => $retNode,
                'var_dump' => $funcCall
            ],
            $nodes
        );
    }

    private function buildDep(Node $node, int $line, int $col, int $type): NodeDependency
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

    private function getTestContent(): string
    {
        return <<<'CODE'
<?php

function test(ArgDep $foo): ReturnDep
{
    var_dump($foo);
}
CODE;
    }
}
