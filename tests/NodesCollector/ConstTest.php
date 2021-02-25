<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;

final class ConstTest extends AbstractIntegrationTest
{

    /** @test */
    public function it_collects_function_dependency_on_const(): void
    {
        $file = new SourceFile('name', $this->getFuncTestContent());
        $nodes = (new NodeExtractor())->extract($file);

        $const = $this->buildNode('CONST_NAME');
        $funcCall = $this->buildNode('var_dump');
        $deps = [
            $this->buildDep($const, 5, 0, NodeDependency::GLOBAL_CONST),
            $this->buildDep($funcCall, 5, 0, NodeDependency::CALL),
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


    protected function getFuncTestContent(): string
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
