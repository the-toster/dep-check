<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Expr\StaticCall;

final class StaticMethodCallHandler extends AbstractHandler
{
    public function handle(StaticCall $node): void
    {
        $parent = $this->findContext($node);
        if($parent) {
            $parent->addDependency($this->getDependency($node->class, NodeDependency::CALL));
        }
    }
}
