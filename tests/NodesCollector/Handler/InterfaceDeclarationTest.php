<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use Tests\Helper\NodesGraphConverter;

final class InterfaceDeclarationTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_handle_just_decl(): void
    {
        $file = new SourceFile('name', $this->getDeclOnly());
        $nodes = (new NodeExtractor())->extract($file);
        $arr = (new NodesGraphConverter())->toIds($nodes);
        $this->assertEquals(['TestData\Cl\Int' => []], $arr);
    }

    /** @test */
    public function it_can_handle_extend(): void
    {
        $file = new SourceFile('name', $this->getDeclWithExtend());
        $nodes = (new NodeExtractor())->extract($file);
        $arr = (new NodesGraphConverter())->toIds($nodes);
        $this->assertEquals(
            [
                'TestData\Cl\Int' => ['Other\Int2', 'TestData\Cl\Int3'],
                'Other\Int2' => [],
                'TestData\Cl\Int3' => []
            ],
            $arr
        );
    }

    protected function getDeclOnly(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

interface Int {
}
CODE;
    }

    private function getDeclWithExtend()
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;
use Other\Int2;
interface Int extends Int2, Int3 {
}
CODE;
    }
}
