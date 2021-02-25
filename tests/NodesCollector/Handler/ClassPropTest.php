<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassProperty;
use DepCheck\NodesCollector\Handlers\NodeCollection;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;

final class ClassPropTest
{
    /** @test */
    public function it_collects_prop_type(): void
    {
        $handler = new ClassProperty(new NodeCollection());
        $prop = new Property(0,
                             [new PropertyProperty('name')],
                             ['parent'=>new Name('SomeClass')],

        );
    }
}
