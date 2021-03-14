<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;



use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node\Stmt\Class_;

final class ClassMethod extends AbstractHandler
{
    public function handle(\PhpParser\Node\Stmt\ClassMethod $node): void
    {
        /** @var Class_ $classDecl */
        $classDecl = $node->getAttribute('parent');
        $parent = $this->populateNode($classDecl);

        if ($node->getDocComment()) {
            $docComment = $node->getDocComment()->getText();

            $paramTypesFromDocblock = $this->getTypesFromDocblock($docComment, 'param');
            foreach ($paramTypesFromDocblock as $type) {
                $this->handleTypeOccurrence($type, $parent, NodeDependency::PARAM);
            }

            $returnTypesFromDocblock = $this->getTypesFromDocblock($docComment, 'return');
            foreach ($returnTypesFromDocblock as $type) {
                $this->handleTypeOccurrence($type, $parent, NodeDependency::RETURN);
            }
        }

        foreach($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $parent, NodeDependency::PARAM);
        }

        if(isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $parent, NodeDependency::RETURN);
        }
    }
}
