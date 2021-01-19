<?php
declare(strict_types=1);

namespace DepCheck\Report;


use DepCheck\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\DependencyChecker\Result\UnknownElement;

final class UnknownReport
{
    /** @var Element[] */
    public array $elements = [];
    public int $total = 0;

    public function add(UnknownElement $record) {
        $this->elements[$record->element->id] = $record->element;
        $this->total++;
    }
}
