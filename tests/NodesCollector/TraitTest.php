<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Helper\NodesGraphConverter;

final class TraitTest extends TestCase
{
    /** @test */
    public function it_can_collect_from_use(): void
    {
        $this->assertEquals(
            [
                'TestData\Cl\C' => ['TestData\Cl\Tr'],
                'TestData\Cl\Tr' => []
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace TestData\Cl;

class C {
 use Tr;
}
CODE)
        );
    }
    /** @test */
    public function it_can_collect_from_decl(): void
    {
        $this->assertEquals(
            [
                'TestData\Cl\Tr' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace TestData\Cl;

trait Tr {
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
