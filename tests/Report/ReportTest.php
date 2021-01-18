<?php
declare(strict_types=1);

namespace Tests\Report;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;
use DepCheck\DependencyChecker\Result\Allowed;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;
use DepCheck\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\DependencyChecker\Result\UnknownElement;
use DepCheck\Report\Report;

/**
 * @covers \DepCheck\Report\Report
 * @covers \DepCheck\Report\Summary
 */
final class ReportTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic(): void
    {
        $l = new Layer('');
        $e = new Element('', $l, []);
        $records = [
            // 3 allowed
            new Allowed($e, $e),
            new Allowed($e, $e),
            new Allowed($e, $e),

            // 2 unknown
            new UnknownElement($e),
            new UnknownElement($e),

            //not used
            new UnknownDependsOn($e, $e),
            new UnknownDependsOnUnknown($e, $e),

            //4 forbidden
            new DependsOnUnknown($e, $e),
            new DependsOnUnknown($e, $e),
            new Forbidden($e, $e),
            new Forbidden($e, $e),
        ];

        $report = new Report($records);

        $this->assertEquals(3, $report->summary->allowedDependencies);
        $this->assertEquals(2, $report->summary->unknownElements);
        $this->assertEquals(4, $report->summary->violations);

    }
}
