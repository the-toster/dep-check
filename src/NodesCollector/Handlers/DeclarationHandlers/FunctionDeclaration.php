<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;

use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node\Stmt\Function_;

final class FunctionDeclaration extends AbstractHandler
{
    public function handle(Function_ $node): void
    {
        $funcDecl = $this->populateNode($node);

        foreach($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $funcDecl, NodeDependency::PARAM);
        }

        if(isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $funcDecl, NodeDependency::RETURN);
        }

    }
}
