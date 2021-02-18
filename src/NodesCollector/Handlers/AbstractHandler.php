<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;

use DepCheck\Model\Input\Node as InputNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use PhpParser\Node as AstNode;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\UnionType;

class AbstractHandler
{
    /**
     * @var InputNode[]
     */
    private array $nodes = [];

    public function getNodes(): array
    {
        return $this->nodes;
    }

    private function extractId(AstNode $node): string
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

    protected function populateNode(AstNode $node): InputNode
    {
        $id = $this->extractId($node);
        if (isset($this->nodes[$id])) {
            return $this->nodes[$id];
        }
        return $this->nodes[$id] = new InputNode($id, [], new Properties(''));
    }

    protected function getDependency(Name $name, int $type): NodeDependency
    {
        $node = $this->populateNode($name);
        $pos = new NodePosition($name->getLine(), 0, '');
        return new NodeDependency($node, $pos, $type);
    }

    protected function handleTypeOccurrence($type, InputNode $parent, int $depType): void
    {
        foreach ($this->getNames($type) as $name) {
            $this->populateNode($name);
            $parent->addDependency($this->getDependency($name, $depType));
        }
    }

    protected function findContext(AstNode $node): ?InputNode
    {
        do {
            $node = $node->getAttribute('parent');
        } while ($node && !$this->isContextNode($node));

        return $node ? $this->populateNode($node) : null;
    }

    private function getNames($type): array
    {
        $r = [];
        $types = $type instanceof Name ? [$type] : [];
        $types = $type instanceof UnionType ? $type->types : $types;

        foreach ($types as $t) {
            if ($t instanceof Name) {
                $r[] = $t;
            }
        }

        return $r;
    }

    /**
     *  node types that can depend on something:
     *  - function, class, interface, trait?
     */
    private function isContextNode(AstNode $node): bool
    {
        $contextNodes = [Function_::class, Class_::class, Interface_::class, Trait_::class];
        return in_array(get_class($node), $contextNodes);
    }
}
