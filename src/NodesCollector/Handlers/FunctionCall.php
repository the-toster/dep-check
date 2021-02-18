<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Expr\FuncCall;

final class FunctionCall extends AbstractHandler
{
    public function handle(FuncCall $node): void
    {
        $this->populateNode($node);
        $parent = $this->findContext($node);
        if ($parent) {
            $dependency = $this->getDependency($node->name, NodeDependency::CALL);
            $parent->addDependency($dependency);
        }
    }

}
