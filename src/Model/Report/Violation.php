<?php
declare(strict_types=1);

namespace DepCheck\Model\Report;


use DepCheck\Model\DependencyChecker\Dependency;

final class Violation
{
    public Dependency $dependency;
    public bool $isForbidden;
    public bool $isUnknown;

    public function __construct(Dependency $dependency)
    {
        $this->dependency = $dependency;
        $this->isForbidden = $dependency->on->layerUnknown();
        $this->isUnknown = !$this->isForbidden;
    }

    public function getElementId(): string
    {
        return $this->dependency->on->id;
    }
}
