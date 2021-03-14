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
        $funcDecl = $this->populateNode($node);

        if ($docComment = $node->getDocComment()) {
            $types = $this->getTypesFromDocblock($docComment->getText(), 'param');
            var_dump($types);
        }

        foreach ($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $funcDecl, NodeDependency::PARAM);
        }

        if (isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $funcDecl, NodeDependency::RETURN);
        }
    }

}
