<?php
declare(strict_types=1);

namespace Tests\Report;


use DepCheck\DependencyChecker\Dependency;
use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;
use DepCheck\DependencyChecker\Position;
use DepCheck\DependencyChecker\Result\Allowed;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;
use DepCheck\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\DependencyChecker\Result\UnknownElement;
use DepCheck\DependencyChecker\Rules;
use DepCheck\DependencyChecker\Service;
use DepCheck\Report\Report;

/**
 * @covers \DepCheck\Report\Report
 * @covers \DepCheck\Report\Summary
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
