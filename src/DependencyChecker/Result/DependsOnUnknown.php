<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;

final class DependsOnUnknown
{
    public Element $fromEl;
    public Layer $fromLayer;
    public Element $toEl;

    public function __construct(Element $fromEl, Layer $fromLayer, Element $toEl)
    {
        $this->fromEl = $fromEl;
        $this->fromLayer = $fromLayer;
        $this->toEl = $toEl;
    }

}
