<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;
use PhpParser\Node\Name;

final class DocBlockService
{
    /**
     * @param string $docComment
     * @param string $tagName
     * @return string[]
     */
    public function getTypesFromDocblock(string $docComment, string $tagName): array
    {
        $types = [];
        $factory = DocBlockFactory::createInstance();
        $surrogateContext = new Context('Surrogate');
        $docblock = $factory->create($docComment, $surrogateContext);
        foreach ($docblock->getTagsByName($tagName) as $tag) {
            if ($tag instanceof TagWithType && $type = $tag->getType()) {
                $types = array_merge($types, $this->extractSingularTypes($type));
            }
        }

        return $this->convertToNames($types, new Context('Surrogate'));
    }

    /**
     * @param Fqsen[] $types
     * @param Context $context
     * @return string[]
     */
    private function convertToNames(array $types, Context $context): array
    {
        $surrogate = '\\'.trim($context->getNamespace(), '\\').'\\';
        $names = [];
        foreach ($types as $fqsen) {
            $name = $fqsen->__toString();
            if(strpos($name, $surrogate) === 0) {
                $name = substr($name, strlen($surrogate));
            }
            $names[] = $name;
        }

        return $names;
    }

    /**
     * @param Type $type
     * @return Fqsen[]
     */
    private function extractSingularTypes(Type $type): array
    {
        if ($type instanceof Object_) {
            $name = $type->getFqsen();
            if ($name) {
                return [$name];
            }
        }

        if ($type instanceof AbstractList) {
            $types = $type instanceof Collection ? [$type->getFqsen()] : [];
            $keyTypes = $this->extractSingularTypes($type->getKeyType());
            $valueTypes = $this->extractSingularTypes($type->getValueType());
            return array_merge($types, $keyTypes, $valueTypes);
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
