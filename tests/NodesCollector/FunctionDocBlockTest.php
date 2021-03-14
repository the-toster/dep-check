<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use Tests\Helper\NodesGraphConverter;

final class FunctionDocBlockTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_collect_param(): void
    {
        $this->assertEquals(
            [
                'RootNs\\SubNs\\f' => [
                    'RootNs\\SubNs\\SomeClass',
                    'RootNs\\SubNs\\Container',
                    'RootNs\\SubNs\\Item',
                    'Other\\SomeClass',
                    'RootNs\\SubNs\\ArrItem',
                    'GlobalClass',
                    ],
                'RootNs\\SubNs\\SomeClass' => [],
                'RootNs\\SubNs\\Container' => [],
                'RootNs\\SubNs\\Item' => [],
                'Other\\SomeClass' => [],
                'RootNs\\SubNs\\ArrItem' => [],
                'GlobalClass' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace RootNs\SubNs;
use Other\SomeClass;
/**
* @param \GlobalClass|Container<Item>|SomeClass|ArrItm[] $a
* @return bool
*/
function f($a, $b): bool {
    return false;
}
CODE)
        );
    }

    private function getNodes(string $content): array
    {
        $file = new SourceFile('name', $content);
        $nodes = (new NodeExtractor())->extract($file);
        return (new NodesGraphConverter())->toIds($nodes);
    }
}
