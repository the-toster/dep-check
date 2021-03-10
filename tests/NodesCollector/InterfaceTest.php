<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Helper\NodesGraphConverter;

final class InterfaceTest extends TestCase
{    /** @test */
    public function it_can_collect_from_extends(): void
    {
        $this->assertEquals(
            [
                'TestData\Cl\Int' => ['OtherNs\Int1', 'OtherNs\Int2'],
                'OtherNs\Int1' => [],
                'OtherNs\Int2' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace TestData\Cl;

use OtherNs\Int1;
use OtherNs\Int2;

interface Int extends Int1, Int2 {
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
