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
        $class = $node->getAttribute('parent');

        $classNode = $this->populateNode($class);

        $this->handleTypeOccurrence($node->type, $classNode, NodeDependency::PROPERTY);
    }

}
