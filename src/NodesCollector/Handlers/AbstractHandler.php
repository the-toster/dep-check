<?php
declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;

use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use PhpParser\Node\Name;

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

}
