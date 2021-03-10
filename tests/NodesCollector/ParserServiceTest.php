<?php

declare(strict_types=1);

namespace Tests\NodesCollector;


use DepCheck\NodesCollector\ParserService;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;

final class ParserServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_handle_nullable(): void
    {
        $parser = new ParserService();
        $r = $parser->getTypeNames(new NullableType(new Name(['Ns','Part'])));

        $this->assertEquals([], $r);
    }
}
