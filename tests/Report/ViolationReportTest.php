<?php
declare(strict_types=1);

namespace Tests\Report;


use DepCheck\DependencyChecker\Dependency;
use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;
use DepCheck\DependencyChecker\Position;
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

        $pos = new Position(0, 0);
        $depA = new Dependency($elA, $pos);
        $depB = new Dependency($elB, $pos);
        $depA2 = new Dependency($elA, new Position(1, 0));
        $depOnUnknown = new Dependency($elUnknown, $pos);

        $report->addForbidden(new Forbidden($elC, $depA));
        $report->addForbidden(new Forbidden($elC, $depB));
        $report->addForbidden(new Forbidden($elB, $depA));
        $report->addForbidden(new Forbidden($elB, $depA2));
        $report->addDependsOnUnknown(new DependsOnUnknown($elC, $depOnUnknown));
        $report->addDependsOnUnknown(new DependsOnUnknown($elB, $depOnUnknown));

        $this->assertEquals([
            'C' => [
                'A' => [new Violation($depA)],
                'B' => [new Violation($depB)],
                'Unk' => [new Violation($depOnUnknown)]
            ],
            'B' => [
                'A' => [new Violation($depA), new Violation($elA)],
                'Unk' => [new Violation($elUnknown)],
            ]
        ], $report->elementViolations);
    }
}
