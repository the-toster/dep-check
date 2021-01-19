<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Dependency;
use DepCheck\DependencyChecker\Element;

final class UnknownDependsOn extends AbstractReportRecord
{
    public Element $fromEl;
    public Dependency $dependency;

    public function __construct(Element $fromEl, Dependency $dependency)
    {
        $this->fromEl = $fromEl;
        $this->dependency = $dependency;
    }

}
