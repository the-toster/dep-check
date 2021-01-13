<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


use DepCheck\DependencyChecker\Result\AbstractReportRecord;
use DepCheck\DependencyChecker\Result\Allowed;
use DepCheck\DependencyChecker\Result\AllowedItem;
use DepCheck\DependencyChecker\Result\DependsOnUnknown;
use DepCheck\DependencyChecker\Result\Forbidden;
use DepCheck\DependencyChecker\Result\Report;
use DepCheck\DependencyChecker\Result\UnknownDependsOn;
use DepCheck\DependencyChecker\Result\UnknownDependsOnUnknown;
use DepCheck\DependencyChecker\Result\UnknownElement;

final class Checker
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
                    $r->addItem(new UnknownDependsOnUnknown($element, $dependency->on));
                }

                $r->addItems($this->getDependencyResults($element, $dependency->on));
            }
        }
        return $r;
    }

    /**
     * @param Element $element
     * @param Element $on
     * @return AbstractReportRecord[]
     */
    private function getDependencyResults(Element $element, Element $on): array
    {
        $r = [];

        if($element->layerUnknown()) {
            foreach ($on->layers as $toLayer) {

                $r[] = new UnknownDependsOn($element, $on, $toLayer);
            }
        }

        foreach ($element->layers as $fromLayer){
            if($on->layerUnknown()) {
                $r[] = new DependsOnUnknown($element, $fromLayer, $on);
            }

            foreach ($on->layers as $toLayer) {
                if($this->rules->has($fromLayer, $toLayer)) {
                    $r[] = new Allowed($element, $fromLayer, $on, $toLayer);
                } else {
                    $r[] = new Forbidden($element, $fromLayer, $on, $toLayer);
                }
            }
        }

    }

}
