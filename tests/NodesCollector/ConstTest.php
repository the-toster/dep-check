<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;

final class ConstTest extends AbstractIntegrationTest
{
    /**
     * @covers \DepCheck\NodesCollector\Visitor
     * @covers \DepCheck\NodesCollector\NodeExtractor
     * @test
     */
    public function it_collects_function_dependency_on_const(): void
    {
        //function declaration can depends by arguments and return type
        //also, it is node itself, something other can depends on it
        $file = new SourceFile('name', $this->getTestContent());
        $nodes = (new NodeExtractor())->extract($file);

        $const = $this->buildNode('CONST_NAME');
        $funcCall = $this->buildNode('var_dump');
        $deps = [
            $this->buildDep($funcCall, 5, 0, NodeDependency::CALL),
            $this->buildDep($const, 5, 0, NodeDependency::GLOBAL_CONST)
        ];
        $funcNode = $this->buildNode('test', $deps);

        $this->assertEquals(
            [
                'var_dump' => $funcCall,
                'test' => $funcNode,
                'CONST_NAME' => $const
            ],
            $nodes
        );
    }



    protected function getTestContent(): string
    {
        return <<<'CODE'
<?php

function test()
{
    var_dump(CONST_NAME);
}
CODE;
    }
}
