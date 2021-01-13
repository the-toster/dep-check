<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;

final class Allowed extends AbstractReportRecord
{
    public Element $fromEl;
    public Layer $fromLayer;
    public Element $toEl;
    public Layer $toLayer;

    public function __construct(Element $fromEl, Layer $fromLayer, Element $toEl, Layer $toLayer)
    {
        $this->fromEl = $fromEl;
        $this->fromLayer = $fromLayer;
        $this->toEl = $toEl;
        $this->toLayer = $toLayer;
    }


}
