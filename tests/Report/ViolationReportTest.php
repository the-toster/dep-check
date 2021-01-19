<?php
declare(strict_types=1);

namespace Tests\Report;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;
use DepCheck\Report\Violation;
use DepCheck\Report\ViolationReport;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DepCheck\Report\ViolationReport
 * @covers \DepCheck\Report\Violation
 */
final class ViolationReportTest extends TestCase
{
    /** @test */
    public function it_summarize_records(): void
    {
        $report = new ViolationReport();

        $elA = new Element('A', new Layer('A'), []);
        $elB = new Element('B', new Layer('B'), []);
        $elC = new Element('C', new Layer('C'), []);
        $elUnknown = new Element('Unk', null, []);

        $report->addForbidden(new Forbidden($elC, $elA));
        $report->addForbidden(new Forbidden($elC, $elB));
        $report->addForbidden(new Forbidden($elB, $elA));
        $report->addForbidden(new Forbidden($elB, $elA));
        $report->addDependsOnUnknown(new DependsOnUnknown($elC, $elUnknown));
        $report->addDependsOnUnknown(new DependsOnUnknown($elB, $elUnknown));

        $this->assertEquals([
            'C' => [
                'A' => [new Violation($elA)],
                'B' => [new Violation($elB)],
                'Unk' => [new Violation($elUnknown)]
            ],
            'B' => [
                'A' => [new Violation($elA), new Violation($elA)],
                'Unk' => [new Violation($elUnknown)],
            ]
        ], $report->elementViolations);
    }
}
