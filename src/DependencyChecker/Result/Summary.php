<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


final class Summary
{
    public int $total = 0;
    public int $allowed = 0;
    public int $violate = 0;
    public int $unknown = 0;

    /** @var Item[] $items */
    public array $items;

    /**
     * @param Item[] $items
     */
    public function addItems(array $items): void
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
        $this->total += 1;
        $this->allowed += $item->isAllowed();
        $this->violate += $item->isViolate();
        $this->unknown += $item->isUnknown();
    }
}
