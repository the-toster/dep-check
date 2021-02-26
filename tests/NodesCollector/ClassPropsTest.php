<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Helper\NodesGraphConverter;

final class ClassPropsTest extends TestCase
{
    /** @test */
    public function it_can_collect_class_props_dependencies(): void
    {
        $file = new SourceFile('name', $this->getClassWithProps());
        $nodes = (new NodeExtractor())->extract($file);
        $arr = (new NodesGraphConverter())->toIds($nodes);

        $this->assertEquals(
            [
                'TestData\Cl\C' => [
                    'OtherNs\PropType',
                    'TestData\Cl\LocalPropType'
                ],
                'OtherNs\PropType' => [],
                'TestData\Cl\LocalPropType' => [],
            ],
            $arr
        );
    }

    /** @test */
    public function it_can_collect_trait_props_dependencies(): void
    {
        $file = new SourceFile('name', $this->getTraitWithProps());
        $nodes = (new NodeExtractor())->extract($file);
        $arr = (new NodesGraphConverter())->toIds($nodes);

        $this->assertEquals(
            [
                'TestData\Cl\Tr' => [
                    'OtherNs\PropType',
                ],
                'OtherNs\PropType' => []
            ],
            $arr
        );
    }

    protected function getClassWithProps(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

use OtherNs\PropType;

final class C {
    private PropType $p1;
    private LocalPropType $p1;
    private int $p2;
    private $p3;
}
CODE;
    }

    protected function getTraitWithProps(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

use OtherNs\PropType;

trait Tr {
    private PropType $p1;
}
CODE;
    }
}
