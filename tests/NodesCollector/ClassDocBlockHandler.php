<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use Tests\Helper\NodesGraphConverter;

final class ClassDocBlockHandler extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_collect(): void
    {
        $this->assertEquals(
            [
                'RootNs\\SubNs\\Cl' => [
                    'Other\\SomeClass',
                    'RootNs\\SubNs\\ClassA',
                    'RootNs\\SubNs\\ClassB',
                    'Ret',
                    'Par',
                ],
                'Other\\SomeClass' => [],
                'RootNs\\SubNs\\ClassA' => [],
                'RootNs\\SubNs\\ClassB' => [],
                'Ret' => [],
                'Par' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace RootNs\SubNs;
use Other\SomeClass;
/**
 * @property SomeClass $some
 * @property-read ClassA $ca
 * @property-write ClassB $cb
 * @method \Ret setString(\Par $p)
 */
class Cl {

    function f($b) {
        //...
    }
}
CODE));
    }

    private function getNodes(string $content): array
    {
        $file = new SourceFile('name', $content);
        $nodes = (new NodeExtractor())->extract($file);
        return (new NodesGraphConverter())->toIds($nodes);
    }
}
