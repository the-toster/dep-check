<?php
declare(strict_types=1);

namespace Tests\Report;


use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Layer;
use DepCheck\Model\DependencyChecker\Position;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;
use DepCheck\Model\Report\Violation;
use DepCheck\Model\Report\ViolationReport;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DepCheck\Model\Report\ViolationReport
 * @covers \DepCheck\Model\Report\Violation
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
                'A' => [new Violation($depA), new Violation($depA2)],
                'Unk' => [new Violation($depOnUnknown)],
            ]
        ], $report->elementViolations);
    }
}
