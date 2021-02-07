<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Function_;

final class FunctionCallHandler extends AbstractHandler
{
    public function handle(FuncCall $node): void
    {
        $id = $this->getId($node->name);
        $this->populateNode($id);

        $parent = $this->getParent($node);
        if ($parent) {
            $callerId = $this->getId($parent->namespacedName);
            $calledFrom = $this->populateNode($callerId);
            $dependency = $this->getDependency($node->name, NodeDependency::CALL);
            $calledFrom->addDependency($dependency);
        }
    }

    private function getParent($node): ?\PhpParser\Node
    {
        $level = $node;
        do {
            $parent = $level->getAttribute('parent');
            $contextFound = in_array(get_class($parent), [Function_::class]);
            $level = $parent;
        } while ($parent && !$contextFound);

        return $parent;
    }
}
