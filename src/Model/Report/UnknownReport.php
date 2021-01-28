<?php
declare(strict_types=1);

namespace DepCheck\Model\Report;


use DepCheck\Model\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\Model\DependencyChecker\Result\UnknownElement;

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
