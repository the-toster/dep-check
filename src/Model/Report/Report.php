<?php
declare(strict_types=1);

namespace DepCheck\Model\Report;


use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\Model\DependencyChecker\Result\Allowed;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;
use DepCheck\Model\DependencyChecker\Result\Report as DepReport;
use DepCheck\Model\DependencyChecker\Result\UnknownElement;

final class Report
{
    public Summary $summary;
    public UnknownReport $unknown;
    public ViolationReport $violations;

    /**
     * @param AbstractReportRecord[]
     */
    public function __construct($records) {
        $this->unknown = $this->buildUnknown($records);
        $this->violations = $this->buildViolations($records);
        $this->summary = new Summary($this->unknown->total, $this->violations->total, $this->countAllowed($records));
    }

    private function buildUnknown(array $records): UnknownReport
    {
        $r = new UnknownReport;
        foreach ($records as $record) {
            if($record instanceof UnknownElement) {
                $r->add($record);
            }
        }
        return $r;
    }

    private function countAllowed(array $records): int
    {
        $r = 0;
        foreach ($records as $record) {
            if($record instanceof Allowed) {
                $r++;
            }
        }
        return $r;
    }

    private function buildViolations(array $violateRecords): ViolationReport
    {
        $r = new ViolationReport();
        foreach ($violateRecords as $record) {
            if($record instanceof Forbidden){
                $r->addForbidden($record);
            }

            if($record instanceof DependsOnUnknown) {
                $r->addDependsOnUnknown($record);
            }
        }
        return $r;
    }
}
