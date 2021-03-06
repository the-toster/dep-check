<?php
declare(strict_types=1);

namespace Tests\DependencyChecker;

use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Layer;
use DepCheck\Model\DependencyChecker\Position;
use DepCheck\Model\DependencyChecker\Result\Allowed;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;
use DepCheck\Model\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\Model\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\UnknownElement;
use DepCheck\Model\DependencyChecker\Service;
use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Rules;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DepCheck\Model\DependencyChecker\Service
 * @covers \DepCheck\Model\DependencyChecker\Rules
 * @covers \DepCheck\Model\DependencyChecker\Element
 * @covers \DepCheck\Model\DependencyChecker\Layer
 * @covers \DepCheck\Model\DependencyChecker\Dependency
 * @covers \DepCheck\Model\DependencyChecker\Result\Report
 * @covers \DepCheck\Model\DependencyChecker\Position
 *
 */
class ServiceTest extends TestCase
{

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\Allowed
     * @covers \DepCheck\Model\DependencyChecker\Result\Forbidden
     */
    public function testComplex(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $layerC = new Layer('c');
        $rules->add($layerA, $layerB);
        $rules->add($layerA, $layerC);
        $rules->add($layerB, $layerC);
        $checker = new Service($rules);

        $pos = new Position(0, 0);

        $elC = new Element('idC', $layerC, []);
        $depC = new Dependency($elC, $pos);
        $elB = new Element('idB', $layerB, [$depC]);

        $depB = new Dependency($elC, $pos);
        $elA = new Element('idA', $layerA, [$depB, $depC]);

        $depA = new Dependency($elA, $pos);
        $elC->dependencies[] = $depA ;
        $elements = [$elA, $elB, $elC];

        $result = $checker->check($elements);
        $r = [
            new Allowed($elA, $depB),
            new Allowed($elA, $depC),
            new Allowed($elB, $depC),
            new Forbidden($elC, $depA),
        ];
        $this->assertEquals($r, $result->records);

    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownElement
     */
    public function testTotal(): void
    {
        $rules = new Rules();
        $checker = new Service($rules);

        $elements = [new Element('id', null, []), new Element('id2', null, [])];

        $result = $checker->check($elements);

        $this->assertEquals(2, $result->total);
    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownElement
     */
    public function testUnknown(): void
    {
        $rules = new Rules();
        $checker = new Service($rules);

        $elements = [new Element('id', null, [])];

        $result = $checker->check($elements);

        $this->assertEquals([new UnknownElement($elements[0])], $result->records);
    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\Allowed
     */
    public function testAllowed(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $rules->add($layerA, $layerB);
        $checker = new Service($rules);


        $el2 = new Element('id2', $layerB, []);
        $dep = new Dependency($el2, new Position(0, 0));
        $el1 = new Element('id1', $layerA, [$dep]);
        $elements = [$el1, $el2];

        $result = $checker->check($elements);
        $this->assertEquals([new Allowed($el1, $dep)], $result->records);

    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\Forbidden
     */
    public function testForbidden(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $rules->add($layerB, $layerA);
        $checker = new Service($rules);


        $el2 = new Element('id2', $layerB, []);
        $dep = new Dependency($el2, new Position(0, 0));
        $el1 = new Element('id1', $layerA, [$dep]);
        $elements = [$el1, $el2];

        $result = $checker->check($elements);

        $this->assertEquals([new Forbidden($el1, $dep)], $result->records);
    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownElement
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownDependsOn
     */
    public function testUnknownDependsOn(): void
    {
        $layerB = new Layer('b');
        $el2 = new Element('id2', $layerB, []);
        $dep = new Dependency($el2, new Position(0, 0));
        $el1 = new Element('id1', null, [$dep]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);

        $this->assertEquals([new UnknownElement($el1), new UnknownDependsOn($el1, $dep)], $result->records);
    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownElement
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownDependsOnUnknown
     */
    public function testUnknownDependsOnUnknown(): void
    {
        $el2 = new Element('id2', null, []);
        $dep = new Dependency($el2, new Position(0, 0));
        $el1 = new Element('id1', null, [$dep]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);
        $r = [
            new UnknownElement($el1),
            new UnknownDependsOnUnknown($el1, $dep),
            new UnknownElement($el2)
            ];
        $this->assertEquals($r, $result->records);
    }

    /**
     * @covers \DepCheck\Model\DependencyChecker\Result\UnknownElement
     * @covers \DepCheck\Model\DependencyChecker\Result\DependsOnUnknown
     */
    public function testDependsOnUnknown(): void
    {
        $layerA = new Layer('a');
        $el2 = new Element('id2', null, []);
        $dep = new Dependency($el2, new Position(0, 0));
        $el1 = new Element('id1', $layerA, [$dep]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);
        $this->assertEquals([new DependsOnUnknown($el1, $dep), new UnknownElement($el2)], $result->records);
    }
}
