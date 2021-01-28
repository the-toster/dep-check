<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker;


use DepCheck\Model\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\Model\DependencyChecker\Result\Allowed;
use DepCheck\Model\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\Forbidden;
use DepCheck\Model\DependencyChecker\Result\Report;
use DepCheck\Model\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\Model\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\Model\DependencyChecker\Result\UnknownElement;

final class Service
{
    private Rules $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param Element[] $elements
     * @return Report
     */
    public function check(array $elements): Report
    {
        $r = new Report();
        foreach ($elements as $element) {
            if($element->layerUnknown()) {
                $r->addItem(new UnknownElement($element));
            }

            foreach ($element->dependencies as $dependency) {

                if($dependency->on->layerUnknown() && $element->layerUnknown()) {
                    $r->addItem(new UnknownDependsOnUnknown($element, $dependency));
                }

                $r->addItems($this->getDependencyResults($element, $dependency));
            }
        }
        return $r;
    }

    /**
     * @param Element $element
     * @param Dependency $dependency
     * @return AbstractReportRecord[]
     */
    private function getDependencyResults(Element $element, Dependency $dependency): array
    {
        $r = [];

        if($element->layerUnknown()) {
            if($dependency->on->hasLayer()) {
                $r[] = new UnknownDependsOn($element, $dependency);
            }
            return $r;
        }

        $fromLayer = $element->layer;
        if($dependency->on->layerUnknown()) {
            $r[] = new DependsOnUnknown($element, $dependency);
        } else {
            $toLayer = $dependency->on->layer;
            if($this->rules->has($fromLayer, $toLayer)) {
                $r[] = new Allowed($element, $dependency);
            } else {
                $r[] = new Forbidden($element, $dependency);
            }
        }

        return $r;
    }

}
