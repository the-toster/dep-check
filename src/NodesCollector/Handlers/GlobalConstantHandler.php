<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Expr\ConstFetch;

final class GlobalConstantHandler extends AbstractHandler
{
    public function handle(ConstFetch $node): void
    {
        $parent = $this->findContext($node);
        if ($parent) {
            $dep = $this->getDependency($node, NodeDependency::GLOBAL_CONST);
            $parent->addDependency($dep);
        }
    }

}
