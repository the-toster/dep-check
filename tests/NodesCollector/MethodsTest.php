<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Helper\NodesGraphConverter;

final class MethodsTest extends TestCase
{
    /** @test */
    public function it_can_collect_nullable(): void
    {
        $this->assertEquals(
            [
                'TestData\Cl\C' => [ 'TestData\Cl\Ret'],
                'TestData\Cl\Ret' => []
            ],
            $this->getNodes($this->getClassNullableMethod())
        );
    }
    /** @test */
    public function it_can_collect_from_class(): void
    {
        $this->assertEquals(
            [
                'TestData\Cl\C' => ['TestData\Cl\Par1', 'TestData\Cl\ParNullable', 'TestData\Cl\Ret'],
                'TestData\Cl\Par1' => [],
                'TestData\Cl\ParNullable' => [],
                'TestData\Cl\Ret' => []
            ],
            $this->getNodes($this->getClassWithMethods())
        );
    }

    private function getNodes(string $content): array
    {
        $file = new SourceFile('name', $content);
        $nodes = (new NodeExtractor())->extract($file);
        return (new NodesGraphConverter())->toIds($nodes);
    }


    private function getClassNullableMethod(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

final class C {
    private function m1(): ?Ret
    {
        return null;
    }
}
CODE;
    }

    private function getClassWithMethods(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

final class C {
    private function m1(Par1 $p1, ?ParNullable $p2): ?Ret
    {
        return null;
    }
}
CODE;
    }

    private function getTraitWithMethods(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

use OtherNs\PropType;

trait Tr {
    private function m1(Par $p1, int $p2): Ret
    {
        return new Ret;
    }
}
CODE;
    }

    private function getInterfaceWithMethods(): string
    {
        return <<<'CODE'
<?php
namespace TestData\Cl;

interface Int1 {
    private function m1(Par $p1, int $p2): Ret;
}
CODE;
    }
}
