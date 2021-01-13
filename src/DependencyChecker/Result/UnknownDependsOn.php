<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;

final class UnknownDependsOn extends AbstractReportRecord
{
    public Element $fromEl;
    public Element $toEl;
    public Layer $toLayer;

    public function __construct(Element $fromEl, Element $toEl, Layer $toLayer)
    {
        $this->fromEl = $fromEl;
        $this->toEl = $toEl;
        $this->toLayer = $toLayer;
    }


}
