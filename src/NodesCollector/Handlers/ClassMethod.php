<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;



use DepCheck\Model\Input\NodeDependency;

final class ClassMethod extends AbstractHandler
{
    public function handle(\PhpParser\Node\Stmt\ClassMethod $node): void
    {
        $classDecl = $node->getAttribute('parent');

        foreach($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $classDecl, NodeDependency::PARAM);
        }

        if(isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $classDecl, NodeDependency::RETURN);
        }
    }
}
