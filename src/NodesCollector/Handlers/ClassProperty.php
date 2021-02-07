<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;

final class ClassProperty extends AbstractHandler
{
    public function handle(Property $node): void
    {
        $class = $this->getParent($node);
        $classNode = $this->populateNode($this->getId($class->namespacedName));

        $this->handleTypeOccurrence($node->type, $classNode, NodeDependency::PROPERTY);
    }

    private function getParent(Property $node): Class_
    {
        return $node->getAttribute('parent');
    }
}
