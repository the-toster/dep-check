<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;


use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
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
