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
 */
class ServiceTest extends TestCase
{

    public function testUnknown(): void
    {
        $rules = new Rules();
        $checker = new Service($rules);

        $elements = [new Element('id', [], [])];

        $result = $checker->check($elements);

        $this->assertEquals(1, $result->total);
        $this->assertEquals(new UnknownElement($elements[0]), $result->records[0]);
    }

    public function testAllowed(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $rules->add($layerA, $layerB);
        $checker = new Service($rules);


        $el2 = new Element('id2', [$layerB], []);
        $el1 = new Element('id1', [$layerA], [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $result = $checker->check($elements);

        $this->assertEquals(1, $result->total);
        $this->assertEquals(new Allowed($el1, $layerA, $el2, $layerB), $result->records[0]);

    }

    public function testForbidden(): void
    {
        $rules = new Rules();
        $layerA = new Layer('a');
        $layerB = new Layer('b');
        $rules->add($layerB, $layerA);
        $checker = new Service($rules);


        $el2 = new Element('id2', [$layerB], []);
        $el1 = new Element('id1', [$layerA], [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $result = $checker->check($elements);

        $this->assertEquals(1, $result->total);
        $this->assertEquals(new Forbidden($el1, $layerA, $el2, $layerB), $result->records[0]);
    }

    public function testUnknownDependsOn(): void
    {
        $layerB = new Layer('b');
        $el2 = new Element('id2', [$layerB], []);
        $el1 = new Element('id1', [], [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);

        $this->assertEquals(2, $result->total);
        $this->assertEquals(new UnknownElement($el1), $result->records[0]);
        $this->assertEquals(new UnknownDependsOn($el1, $el2, $layerB), $result->records[1]);
    }

    public function testUnknownDependsOnUnknown(): void
    {
        $el2 = new Element('id2', [], []);
        $el1 = new Element('id1', [], [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);

        $this->assertEquals(2, $result->total);
        $this->assertEquals(new UnknownElement($el1), $result->records[0]);
        $this->assertEquals(new UnknownDependsOnUnknown($el1, $el2), $result->records[1]);
    }

    public function testDependsOnUnknown(): void
    {
        $layerA = new Layer('a');
        $el2 = new Element('id2', [], []);
        $el1 = new Element('id1', [$layerA], [new Dependency($el2)]);
        $elements = [$el1, $el2];

        $checker = new Service(new Rules());
        $result = $checker->check($elements);
        $this->assertEquals(2, $result->total);
        $this->assertEquals(new DependsOnUnknown($el1, $layerA, $el2), $result->records[0]);

        //occurs because checker iterate over el2 too
        $this->assertEquals(new UnknownElement($el2), $result->records[1]);
    }
}
