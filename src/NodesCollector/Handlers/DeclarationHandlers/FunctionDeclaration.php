<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;

use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Function_;

final class FunctionDeclaration extends AbstractHandler
{

    public function handle(Function_ $node): void
    {
        $parent = $this->populateNode($node);

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

        foreach ($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $parent, NodeDependency::PARAM);
        }

        if (isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $parent, NodeDependency::RETURN);
        }
    }

}
