<?php
declare(strict_types=1);

namespace DepCheck\Report;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\DependencyChecker\Result\Allowed;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;
use DepCheck\DependencyChecker\Result\Report as DepReport;
use DepCheck\DependencyChecker\Result\UnknownElement;

final class Report
{
    public Summary $summary;

    /** @var Element[] */
    public array $unknown;

    /** @var AbstractReportRecord[] */
    public array $violations;

    /**
     * @param AbstractReportRecord[]
     */
    public function __construct($records) {
        $this->unknown = $this->getUnknown($records);
        $this->violations = $this->getViolations($records);
        $this->summary = new Summary(count($this->unknown), count($this->violations), $this->countAllowed($records));
    }

    private function getUnknown(array $records): array
    {
        $r = [];
        foreach ($records as $record) {
            if($record instanceof UnknownElement) {
                $r[] = $record->element;
            }
        }
        return $r;
    }

    private function getViolations(array $records): array
    {
        $r = [];
        foreach ($records as $record) {
            if($record instanceof Forbidden) {
                $r[] = $record;
            }

            if($record instanceof DependsOnUnknown) {
                $r[] = $record;
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
}
