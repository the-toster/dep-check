<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker\Result;


use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Element;

final class Forbidden extends AbstractReportRecord
{
    public Element $fromEl;
    public Dependency $dependency;

    public function __construct(Element $fromEl, Dependency $dependency)
    {
        $this->fromEl = $fromEl;
        $this->dependency = $dependency;
    }

}
