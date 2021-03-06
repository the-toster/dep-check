<?php
declare(strict_types=1);

namespace DepCheck\Model\Report;


use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;

final class ViolationReport
{
    public array $elementViolations;
    private array $elements;
    public int $total = 0;
    /**
     * @param Forbidden|DependsOnUnknown $record
     */
    private function addRecord(AbstractReportRecord $record): void
    {
        $this->initElementViolations($record->fromEl);
        $this->addViolation($record->fromEl, new Violation($record->dependency));
        $this->total++;
    }

    public function addForbidden(Forbidden $forbidden): void
    {
        $this->addRecord($forbidden);
    }

    public function addDependsOnUnknown(DependsOnUnknown $dependsOnUnknown): void
    {
        $this->addRecord($dependsOnUnknown);
    }

    private function initElementViolations(Element $fromEl)
    {
        if(!isset($this->elementViolations[$fromEl->id])) {
            $this->elementViolations[$fromEl->id] = [];
            $this->elements[$fromEl->id] = $fromEl;
        }
    }

    private function addViolation(Element $from, Violation $violation): void
    {
        if(!isset($this->elementViolations[$from->id][$violation->getElementId()])) {
            $this->elementViolations[$from->id][$violation->getElementId()] = [];
        }
        $this->elementViolations[$from->id][$violation->getElementId()][] = $violation;
    }

    public function getElement(string $id): Element
    {
        if(!isset($this->elements[$id])) {
            throw new \InvalidArgumentException('Element not found');
        }
        return $this->elements[$id];
    }
}
