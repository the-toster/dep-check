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
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Function_;

final class FunctionDeclaration extends AbstractHandler
{
    private function getTypesFromDocblock(string $docComment, string $tagName): array
    {
        $types = [];
        $factory = DocBlockFactory::createInstance();
        $docblock = $factory->create($docComment, new Context('Surrogate'));
        foreach ($docblock->getTagsByName($tagName) as $tag) {
            if ($tag instanceof TagWithType && $type = $tag->getType()) {
                $types = array_merge($types, $this->extractSingularTypes($type));
            }
        }
        return $types;
    }

    public function handle(Function_ $node): void
    {
        $funcDecl = $this->populateNode($node);

        if ($docComment = $node->getDocComment()) {
            $types = $this->getTypesFromDocblock($docComment->getText());
        }

        foreach ($node->params as $param) {
            $this->handleTypeOccurrence($param->type, $funcDecl, NodeDependency::PARAM);
        }

        if (isset($node->returnType)) {
            $this->handleTypeOccurrence($node->returnType, $funcDecl, NodeDependency::RETURN);
        }
    }

    private function extractSingularTypes(Type $type): array
    {
        if ($type instanceof Object_) {
            $name = $type->getFqsen();
            if ($name) {
                return [$name];
            }
        }

        if ($type instanceof AbstractList) {
            $types = $this->extractSingularTypes($type->getKeyType());
            return array_merge($types, $this->extractSingularTypes($type->getValueType()));
        }

        if ($type instanceof Compound) {
            $types = [];
            foreach ($type as $item) {
                $types = array_merge($types, $this->extractSingularTypes($item));
            }
            return $types;
        }

        return [];
    }
}
