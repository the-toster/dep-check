<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\UnionType;

final class ParserService
{
    public function extractId(Node $node): string
    {
        if($node instanceof Name) {
            $name = $node;
        } elseif(isset($node->namespacedName)) {
            $name = $node->namespacedName;
        } elseif (isset($node->name)) {
            $name = $node->name;
        } else {
            throw new \RuntimeException('Cant find name of node: '.get_class($node));
        }

        return implode('\\', $name->parts);
    }

    public function getTypeNames($type): array
    {
        $r = [];
        $types = $type instanceof Name ? [$type] : [];
        $types = $type instanceof Node\NullableType ? [$type->type] : $types;
        $types = $type instanceof UnionType ? $type->types : $types;

        foreach ($types as $t) {
            if ($t instanceof Name) {
                $r[] = $t;
            }
        }

        return $r;
    }

    public function findContextNode(Node $node): ?Node
    {
        do {
            $node = $node->getAttribute('parent');
        } while ($node && !$this->isContextNode($node));
        return $node;
    }

    /**
     *  node types that can depend on something:
     *  - function, class, interface, trait?
     */
    private function isContextNode(Node $node): bool
    {
        $contextNodes = [Function_::class, Class_::class, Interface_::class, Trait_::class];
        return in_array(get_class($node), $contextNodes);
    }
}
