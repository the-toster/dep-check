<?php

declare(strict_types=1);

namespace Tests\NodesCollector\Handler;


use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\Handlers\ClassMethod as ClassMethodHandler;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod as ClassMethodNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DepCheck\NodesCollector\Handlers\ClassMethod
 */
final class ClassMethodTest extends TestCase
{


    /** @test */
    public function it_collects_return_type(): void
    {
        $method = new ClassMethodNode('method1', [], ['parent'=>$this->getParent()]);
        $method->returnType = new Name('RetType');

        $handler = new ClassMethodHandler();
        $handler->handle($method);

        $ret = $this->buildNode('RetType');
        $deps = [$this->buildDep($ret, -1, 0, NodeDependency::RETURN)];
        $r = [
            'someClass'=>$this->buildNode('someClass', $deps),
            'RetType' => $ret
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

        $handler = new ClassMethodHandler();
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

        $handler = new ClassMethodHandler();
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
