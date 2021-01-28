<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker\Result;


use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Element;

final class UnknownDependsOnUnknown extends AbstractReportRecord
{
    public Element $from;
    public Dependency $dependency;

    public function __construct(Element $fromEl, Dependency $dependency)
    {
        $this->fromEl = $fromEl;
        $this->dependency = $dependency;
    }

}
