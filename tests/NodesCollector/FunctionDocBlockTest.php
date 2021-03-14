<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use Tests\Helper\NodesGraphConverter;

final class FunctionDocBlockTest extends \PHPUnit\Framework\TestCase
{

    /** @test */
    public function it_can_collect_return_type(): void
    {
        $this->assertEquals(
            [
                'RootNs\\SubNs\\f' => [
                    'Other\\SomeClass',
                ],
                'Other\\SomeClass' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace RootNs\SubNs;
use Other\SomeClass;
/**
* @return SomeClass
*/
function f() {
    
}
CODE));
    }
    /** @test */
    public function it_can_collect_complex_param(): void
    {
        $this->assertEquals(
            [
                'RootNs\\SubNs\\f' => [
                    'GlobalClass',
                    'RootNs\\SubNs\\Container',
                    'RootNs\\SubNs\\Item',
                    'Other\\SomeClass',
                    'RootNs\\SubNs\\ArrItem',
                    ],
                'GlobalClass' => [],
                'RootNs\\SubNs\\Container' => [],
                'RootNs\\SubNs\\Item' => [],
                'Other\\SomeClass' => [],
                'RootNs\\SubNs\\ArrItem' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace RootNs\SubNs;
use Other\SomeClass;
/**
* @param \GlobalClass|Container<Item> $a
* @param SomeClass|ArrItem[]|bool $b
* @return bool
*/
function f($a, $b): bool {
    return false;
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
