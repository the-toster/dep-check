<?php
declare(strict_types=1);

namespace DepCheck\Report;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;

final class ElementViolations
{
    public Element $element;

    /** @var Violation[] */
    public array $violations;

    public function __construct(Element $element)
    {
        $this->element = $element;
    }

    /**
     * @param Forbidden|DependsOnUnknown $record
     */
    public function addRecord(AbstractReportRecord $record): void
    {
        $this->violations[] = new Violation($record->toElement);
    }
}
