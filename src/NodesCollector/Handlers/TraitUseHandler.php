<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\DependencyChecker\Position;
use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Stmt\TraitUse;

final class TraitUseHandler extends AbstractHandler
{
    public function handle(TraitUse $node): void
    {
        $parent = $this->findContext($node);
        foreach ($node->traits as $target) {
            $on = $this->populateNode($target);
            if($parent) {
                $parent->addDependency($this->getDependency($target, NodeDependency::USES_TRAIT));
            }
        }

    }
}
