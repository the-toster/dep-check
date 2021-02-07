<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;

use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Function_;

final class FunctionDeclarationHandler extends AbstractHandler
{
    public function handle(Function_ $node): void
    {
        $id = $this->getId($node->namespacedName);
        $checkNode = $this->populateNode($id);

        foreach($node->params as $param) {
            /** @var Param $param */
            $paramDep = $this->getDependency($param->type, NodeDependency::PARAM);
            $checkNode->addDependency($paramDep);
        }

        if(isset($node->returnType)) {
            $retDep = $this->getDependency($node->returnType, NodeDependency::RETURN);
            $checkNode->addDependency($retDep);
        }

    }
}
