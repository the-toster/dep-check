<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\NodesCollector\Handlers\ClassMethod as ClassMethodHandler;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod as ClassMethodNode;
use PHPUnit\Framework\TestCase;

final class ClassMethodTest extends TestCase
{
    /** @test */
    public function it_collects_methods(): void
    {
        $handler = new ClassMethodHandler();
        $method = new ClassMethodNode('method1', [], ['parent'=>new Class_('someClass')]);

        $handler->handle($method);
        var_dump($handler->getNodes());
    }
}
