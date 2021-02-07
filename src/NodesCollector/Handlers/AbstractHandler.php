<?php
declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;

use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;

class AbstractHandler {
    /**
     * @var Node[]
     */
    private array $nodes = [];

    public function getNodes(): array
    {
        return $this->nodes;
    }


    protected function getId(Name $name): string
    {
        return implode('\\', $name->parts);
    }

    protected function populateNode(string $id): CheckNode
    {
        if(isset($this->nodes[$id])) {
            return $this->nodes[$id];
        }
        return $this->nodes[$id] = new CheckNode($id, [], new Properties(''));
    }

    protected function getDependency(Name $name, int $type): NodeDependency
    {
        $id = $this->getId($name);
        $node = $this->populateNode($id);
        $pos = new NodePosition($name->getLine(), 0, '');
        return new NodeDependency($node, $pos, $type);
    }

    protected function handleTypeOccurrence($type, Node $parent, int $depType): void
    {
        foreach ($this->getNames($type) as $name) {
            $this->populateNode($this->getId($name));
            $parent->addDependency($this->getDependency($name, $depType));
        }
    }

    private function getNames($type): array
    {
        $r = [];
        $types = $type instanceof Name ? [$type] : [];
        $types = $type instanceof UnionType ? $type->types : $types;

        foreach ($types as $t) {
            if (get_class($t) instanceof Name) {
                $r[] = $t;
            }
        }

        return $r;
    }
}
