<?php
declare(strict_types=1);

namespace Tests\DependencyChecker;

use DepCheck\DependencyChecker\Dependency;
use DepCheck\DependencyChecker\Layer;
use DepCheck\DependencyChecker\Result\Allowed;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;
use DepCheck\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\DependencyChecker\Result\UnknownElement;
use DepCheck\DependencyChecker\Service;
use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Rules;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DepCheck\DependencyChecker\Service
 * @covers \DepCheck\DependencyChecker\Rules
 * @covers \DepCheck\DependencyChecker\Element
 * @covers \DepCheck\DependencyChecker\Layer
 * @covers \DepCheck\DependencyChecker\Dependency
 * @covers \DepCheck\DependencyChecker\Result\Report
 *
 */
class ServiceTest extends TestCase
{

    /**
     * @covers \DepCheck\DependencyChecker\Result\Allowed
     * @covers \DepCheck\DependencyChecker\Result\Forbidden
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


        $elC = new Element('idC', $layerC, []);
        $elB = new Element('idB', $layerB, [new Dependency($elC)]);
        $elA = new Element('idA', $layerA, [new Dependency($elB), new Dependency($elC)]);
        $elC->dependencies[] = new Dependency($elA);
        $elements = [$elA, $elB, $elC];

        $result = $checker->check($elements);
        $r = [
            new Allowed($elA, $elB),
            new Allowed($elA, $elC),
            new Allowed($elB, $elC),
            new Forbidden($elC, $elA),
        ];
        $this->assertEquals($r, $result->records);

    }

    /**
     * @covers \DepCheck\DependencyChecker\Result\UnknownElement
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
     * @covers \DepCheck\DependencyChecker\Result\UnknownElement
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
     * @covers \DepCheck\DependencyChecker\Result\Allowed
     */
    public function testAllowed(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $rules->add($layerA, $layerB);
        $checker = new Service($rules);


        $el2 = new Element('id2', $layerB, []);
        $el1 = new Element('id1', $layerA, [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $result = $checker->check($elements);
        $this->assertEquals([new Allowed($el1, $el2)], $result->records);

    }

    /**
     * @covers \DepCheck\DependencyChecker\Result\Forbidden
     */
    public function testForbidden(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $rules->add($layerB, $layerA);
        $checker = new Service($rules);


        $el2 = new Element('id2', $layerB, []);
        $el1 = new Element('id1', $layerA, [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $result = $checker->check($elements);

        $this->assertEquals([new Forbidden($el1, $el2)], $result->records);
    }

    /**
     * @covers \DepCheck\DependencyChecker\Result\UnknownElement
     * @covers \DepCheck\DependencyChecker\Result\UnknownDependsOn
     */
    public function testUnknownDependsOn(): void
    {
        $layerB = new Layer('b');
        $el2 = new Element('id2', $layerB, []);
        $el1 = new Element('id1', null, [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);

        $this->assertEquals([new UnknownElement($el1), new UnknownDependsOn($el1, $el2)], $result->records);
    }

    /**
     * @covers \DepCheck\DependencyChecker\Result\UnknownElement
     * @covers \DepCheck\DependencyChecker\Result\UnknownDependsOnUnknown
     */
    public function testUnknownDependsOnUnknown(): void
    {
        $el2 = new Element('id2', null, []);
        $el1 = new Element('id1', null, [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);
        $r = [
            new UnknownElement($el1),
            new UnknownDependsOnUnknown($el1, $el2),
            new UnknownElement($el2)
            ];
        $this->assertEquals($r, $result->records);
    }

    /**
     * @covers \DepCheck\DependencyChecker\Result\UnknownElement
     * @covers \DepCheck\DependencyChecker\Result\DependsOnUnknown
     */
    public function testDependsOnUnknown(): void
    {
        $layerA = new Layer('a');
        $el2 = new Element('id2', null, []);
        $el1 = new Element('id1', $layerA, [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);
        $this->assertEquals([new DependsOnUnknown($el1, $el2), new UnknownElement($el2)], $result->records);
    }
}
