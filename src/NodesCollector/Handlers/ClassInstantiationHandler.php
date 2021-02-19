<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Expr\New_;

final class ClassInstantiationHandler extends AbstractHandler
{
    public function handle(New_ $node): void
    {
        $parent = $this->findContext($node);
        if($parent) {
            $parent->addDependency($this->getDependency($node->class, NodeDependency::INSTANTIATE));
        }
    }
}
