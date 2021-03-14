<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\Model\Input\SourceFile;
use DepCheck\NodesCollector\NodeExtractor;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use Tests\Helper\NodesGraphConverter;

final class PropertyDocBlockTest extends TestCase
{
    /** @test */
    public function it_can_collect_var_type(): void
    {
        $this->assertEquals(
            [
                'RootNs\\SubNs\\Cl' => [
                    'Other\\SomeClass',
                    'RootNs\\SubNs\\ClassB',
                ],
                'Other\\SomeClass' => [],
                'RootNs\\SubNs\\ClassB' => [],
            ],
            $this->getNodes(<<<'CODE'
<?php
namespace RootNs\SubNs;
use Other\SomeClass;

class Cl {
    /**
    * @var SomeClass|ClassB $a
    */
    public $a;
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
