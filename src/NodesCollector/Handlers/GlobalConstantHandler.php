<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Const_;

final class GlobalConstantHandler extends AbstractHandler
{
    public function handle(Const_ $node): void
    {
        $parent = $this->findContext($node);
        if ($parent) {
            $dep = $this->getDependency($node->namespacedName, NodeDependency::GLOBAL_CONST);
            $parent->addDependency($dep);
        }
    }

}
