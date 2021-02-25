<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassMethod as ClassMethodHandler;
use DepCheck\NodesCollector\Handlers\NodeCollection;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod as ClassMethodNode;
use PhpParser\Node\UnionType;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DepCheck\NodesCollector\Handlers\DeclarationHandlers\ClassMethod
 */
final class ClassMethodTest extends TestCase
{


    /** @test */
    public function it_collects_return_type(): void
    {
        $method = new ClassMethodNode('method1', [
            'params'=>[new Param('p', null, new Name('p1'))],
            'returnType' => new UnionType([new Name('r1'), new Name('r2')])
        ], ['parent'=>$this->getParent()]);

        $handler = new ClassMethodHandler(new NodeCollection());
        $handler->handle($method);

        $p1 = $this->buildNode('p1');
        $r1 = $this->buildNode('r1');
        $r2 = $this->buildNode('r2');
        $deps = [
            $this->buildDep($p1, -1, 0, NodeDependency::PARAM),
            $this->buildDep($r1, -1, 0, NodeDependency::RETURN),
            $this->buildDep($r2, -1, 0, NodeDependency::RETURN)
        ];
        $r = [
            'someClass'=>$this->buildNode('someClass', $deps),
            'p1' => $p1,
            'r1' => $r1,
            'r2' => $r2
        ];

        $this->assertEquals($r, $handler->getNodes());
    }


    /** @test */
    public function it_collects_params_types(): void
    {
        $method = new ClassMethodNode('method1', [], ['parent'=>$this->getParent()]);
        $method->params = [
            new Param(new Variable('p1'), null, new Name('Param1')),
            new Param(new Variable('p2'), null, new Name('Param2')),
        ];

        $handler = new ClassMethodHandler(new NodeCollection());
        $handler->handle($method);

        $p1 = $this->buildNode('Param1');
        $p2 = $this->buildNode('Param2');
        $deps = [
            $this->buildDep($p1, -1, 0, NodeDependency::PARAM),
            $this->buildDep($p2, -1, 0, NodeDependency::PARAM),
        ];
        $r = [
            'someClass'=>$this->buildNode('someClass', $deps),
            'Param1' => $p1,
            'Param2' => $p2
        ];

        $this->assertEquals($r, $handler->getNodes());

    }

    /** @test */
    public function it_collects_class(): void
    {
        $method = new ClassMethodNode('method1', [], ['parent'=>$this->getParent()]);

        $handler = new ClassMethodHandler(new NodeCollection());
        $handler->handle($method);


        $r = [
            'someClass'=>$this->buildNode('someClass'),
        ];

        $this->assertEquals($r, $handler->getNodes());

    }

    protected function buildDep(Node $node, int $line, int $col, int $type): NodeDependency
    {
        return new NodeDependency($node, new NodePosition($line, $col, ''), $type);
    }


    private function getParent(): Class_
    {
        $classNode = new Class_('someClass');
        $classNode->namespacedName = new Name('someClass');
        return $classNode;
    }
    /**
     * @param string $id
     * @param NodeDependency[] $deps
     * @return Node
     */
    private function buildNode($id, $deps = []): Node
    {
        return new Node($id, $deps, new Properties(''));
    }
}
