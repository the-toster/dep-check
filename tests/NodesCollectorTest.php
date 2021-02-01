<?php

declare(strict_types=1);

namespace Tests;


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
//      var_dump($nodes);
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
