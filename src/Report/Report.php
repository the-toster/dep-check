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

    public function __construct(DepReport $depReport) {
        $this->fillUnknown($depReport->records);
        $this->fillViolations($depReport->records);
        $allowedNumber = $this->fillAllowed($depReport->records);
    }

    private function fillUnknown(array $records): void
    {
        $r = [];
        foreach ($records as $record) {
            if($record instanceof UnknownElement) {
                $r[] = $record->element;
            }
        }
        $this->unknown = $r;
    }

    private function fillViolations(array $records): void
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

        $this->violations = $r;
    }

    private function fillAllowed(array $records): void
    {
        $r = [];
        foreach ($records as $record) {
            if($record instanceof Allowed) {
                $r[] = $record;
            }
        }
        $this->allowed = $records;
    }
}
