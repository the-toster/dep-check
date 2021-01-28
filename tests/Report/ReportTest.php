<?php
declare(strict_types=1);

namespace Tests\Report;


use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Layer;
use DepCheck\Model\DependencyChecker\Position;
use DepCheck\Model\DependencyChecker\Result\Allowed;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;
use DepCheck\Model\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\Model\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\UnknownElement;
use DepCheck\Model\DependencyChecker\Rules;
use DepCheck\Model\DependencyChecker\Service;
use DepCheck\Model\Report\Report;

/**
 * @covers \DepCheck\Model\Report\Report
 * @covers \DepCheck\Model\Report\Summary
 */
final class ReportTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_count_summary(): void
    {
        $l = new Layer('');
        $e = new Element('el_id', $l, []);
        $dep = new Dependency($e, new Position(0, 0));
        $records = [
            // 3 allowed
            new Allowed($e, $dep),
            new Allowed($e, $dep),
            new Allowed($e, $dep),

            // 2 unknown
            new UnknownElement($e),
            new UnknownElement($e),

            //should skip this
            new UnknownDependsOn($e, $dep),
            new UnknownDependsOnUnknown($e, $dep),

            //4 forbidden
            new DependsOnUnknown($e, $dep),
            new DependsOnUnknown($e, $dep),
            new Forbidden($e, $dep),
            new Forbidden($e, $dep),
        ];

        $report = new Report($records);

        $this->assertEquals(3, $report->summary->allowedDependencies);
        $this->assertEquals(2, $report->summary->unknownElements);
        $this->assertEquals(4, $report->summary->violations);
    }

}
