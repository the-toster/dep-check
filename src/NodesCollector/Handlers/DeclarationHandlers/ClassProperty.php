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
        if ($node->getDocComment()) {
            $docComment = $node->getDocComment()->getText();

            $paramTypesFromDocblock = $this->getTypesFromDocblock($docComment, 'var');
            foreach ($paramTypesFromDocblock as $type) {
                $this->handleTypeOccurrence($type, $classNode, NodeDependency::PROPERTY);
            }
        }
        $this->handleTypeOccurrence($node->type, $classNode, NodeDependency::PROPERTY);
    }

}
