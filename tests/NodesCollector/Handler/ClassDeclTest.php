<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassDeclaration;
use DepCheck\NodesCollector\Handlers\NodeCollection;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;

final class ClassDeclTest extends TestCase
{
    /** @test */
    public function it_collect_class_itself(): void
    {
        $handler = new ClassDeclaration(new NodeCollection());
        $handler->handle(new Class_(new Name('SomeClass')));
        $this->assertEquals(
            [
                'SomeClass' => new Node('SomeClass', [], new Properties(''))
            ],
            $handler->getNodes()
        );
    }

    /** @test */
    public function it_collects_parent(): void
    {
        $handler = new ClassDeclaration(new NodeCollection());
        $handler->handle(new Class_(new Name('SomeClass'), ['extends' => new Name('ParentClass')]));

        $parent = new Node('ParentClass', [], new Properties(''));
        $dep = new NodeDependency($parent, new NodePosition(-1, 0, ''), NodeDependency::EXTENDS);
        $this->assertEquals(
            [
                'ParentClass' => $parent,
                'SomeClass' => new Node('SomeClass', [$dep], new Properties('')),
            ],
            $handler->getNodes()
        );
    }

    /** @test */
    public function it_collects_interfaces(): void
    {
        $handler = new ClassDeclaration(new NodeCollection());
        $handler->handle(new Class_(new Name('SomeClass'), ['implements' => [new Name('I1'), new Name('I2')]]));

        $i1 = new Node('I1', [], new Properties(''));
        $i2 = new Node('I2', [], new Properties(''));
        $dep1 = new NodeDependency($i1, new NodePosition(-1, 0, ''), NodeDependency::IMPLEMENTS);
        $dep2 = new NodeDependency($i2, new NodePosition(-1, 0, ''), NodeDependency::IMPLEMENTS);
        $classNode = new Node('SomeClass', [$dep1, $dep2], new Properties(''));
        $this->assertEquals(
            [
                'SomeClass' => $classNode,
                'I1' => $i1,
                'I2' => $i2
            ],
            $handler->getNodes()
        );
    }
}
