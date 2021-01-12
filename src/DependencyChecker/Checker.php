<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


use DepCheck\DependencyChecker\Result\Item;
use DepCheck\DependencyChecker\Result\Summary;

final class Checker
{
    private Rules $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param Element[] $elements
     * @return Summary
     */
    public function check(array $elements): Summary
    {
        $r = new Summary();
        foreach ($elements as $element) {
            foreach ($element->dependencies as $dependency) {
                $r->addItems($this->getDependencyResults($element, $dependency->on));
            }
        }
        return $r;
    }

    /**
     * @param Element $element
     * @param Element $on
     * @return Item[]
     */
    private function getDependencyResults(Element $element, Element $on): array
    {
        if($element->layerUnknown() || $on->layerUnknown()) {
            return [Item::unknown($element, $on)];
        }

        $r = [];
        foreach ($element->layers as $fromLayer){
            foreach ($on->layers as $toLayer) {
                if($this->rules->has($fromLayer, $toLayer)) {
                    $r[] = Item::allowed($element, $on);
                } else {
                    $r[] = Item::violate($element, $on);
                }
            }
        }

    }

}
