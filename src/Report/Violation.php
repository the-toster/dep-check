<?php
declare(strict_types=1);

namespace DepCheck\Report;


use DepCheck\DependencyChecker\Element;

final class Violation
{
    public Element $toElement;
    public bool $isForbidden;
    public bool $isUnknown;

    public function __construct(Element $toElement)
    {
        $this->toElement = $toElement;
        $this->isForbidden = $toElement->layerUnknown();
        $this->isUnknown = !$this->isForbidden;
    }

}
