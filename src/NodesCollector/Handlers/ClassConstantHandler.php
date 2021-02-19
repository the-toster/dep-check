<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Expr\ClassConstFetch;

final class ClassConstantHandler extends AbstractHandler
{
    public function handle(ClassConstFetch $node): void
    {
        $parent = $this->findContext($node);
        if ($parent) {
            $dep = $this->getDependency($node->class, NodeDependency::CLASS_CONST);
            $parent->addDependency($dep);
        }
    }
}
