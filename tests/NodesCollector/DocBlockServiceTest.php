<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\NodesCollector\DocBlockService;
use PHPUnit\Framework\TestCase;

final class DocBlockServiceTest extends TestCase
{
    /** @test */
    public function it_can_extract_types_from_params(): void
    {
        $docBlockService = new DocBlockService();
        $names = $docBlockService->getTypesFromDocblock(<<<Code
/**
 * @param SomeClass \$a
 * @param \GlobalClass \$a
 * @param Container<Element>|ArrItem[]
 */
Code, 'param');
        $this->assertEquals(['SomeClass', '\GlobalClass', 'Container', 'Element', 'ArrItem'], $names);
    }
}
