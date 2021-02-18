<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;



use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Stmt\Class_;

final class ClassMethod extends AbstractHandler
{
    public function handle(\PhpParser\Node\Stmt\ClassMethod $node): void
    {
        /** @var Class_ $classDecl */
        $classDecl = $node->getAttribute('parent');
        $parent = $this->populateNode($classDecl);
        foreach($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $parent, NodeDependency::PARAM);
        }

        if(isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $parent, NodeDependency::RETURN);
        }
    }
}
