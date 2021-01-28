<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker\Result;

/**
 * Class Summary
 * @package DepCheck\DependencyChecker\Result
 * Displays summary of report records
 */
final class Report
{
    public int $total = 0;

    /** @var AbstractReportRecord[] $records */
    public array $records;

    /**
     * @param AbstractReportRecord[] $items
     */
    public function addItems(array $items): void
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(AbstractReportRecord $item): void
    {
        $this->records[] = $item;
        $this->total += 1;
    }
}
