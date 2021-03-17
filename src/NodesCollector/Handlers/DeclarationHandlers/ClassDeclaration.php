<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;


use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node\Stmt\Class_;

final class ClassDeclaration extends AbstractHandler
{
    public function handle(Class_ $node): void
    {
        $class = $this->populateNode($node);
        if($node->extends) {
            $class->addDependency($this->getDependency($node->extends, NodeDependency::EXTENDS));
        }

        foreach ($node->implements as $interface) {
            $class->addDependency($this->getDependency($interface, NodeDependency::IMPLEMENTS));
        }

        if ($node->getDocComment()) {
            $docComment = $node->getDocComment()->getText();

            foreach (['property', 'property-read', 'property-write'] as $tagName) {
                $paramTypesFromDocblock = $this->getTypesFromDocblock($docComment, $tagName);
                foreach ($paramTypesFromDocblock as $type) {
                    $this->handleTypeOccurrence($type, $class, NodeDependency::PROPERTY);
                }
            }


            $methodsTypes = $this->docBlockService->getTypesFromMethodTags($docComment);
            foreach ($this->convertStringsToNames($methodsTypes['params']) as $type) {
                $this->handleTypeOccurrence($type, $class, NodeDependency::PARAM);
            }
            foreach ($this->convertStringsToNames($methodsTypes['return']) as $type) {
                $this->handleTypeOccurrence($type, $class, NodeDependency::RETURN);
            }
        }
    }
}
