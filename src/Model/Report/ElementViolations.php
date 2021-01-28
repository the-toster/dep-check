<?php
declare(strict_types=1);

namespace DepCheck\Model\Report;


use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;

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
