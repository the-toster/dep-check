<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;

use DepCheck\Model\Input\Node as InputNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\InputService;
use DepCheck\NodesCollector\ParserService;
use PhpParser\Node;
use PhpParser\Node as AstNode;

class AbstractHandler
{

    private ParserService $parser;
    private array $nodes = [];

    public function __construct()
    {
        $this->parser = new ParserService();
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    protected function populateNode(Node $node): InputNode
    {
        $id = $this->parser->extractId($node);
        if (isset($this->nodes[$id])) {
            return $this->nodes[$id];
        }
        return $this->nodes[$id] = new InputNode($id, [], new Properties(''));
    }

    protected function handleTypeOccurrence($type, InputNode $parent, int $depType): void
    {
        foreach ($this->parser->getTypeNames($type) as $name) {
            $parent->addDependency($this->getDependency($name, $depType));
        }
    }

    protected function getDependency(AstNode $on, int $type): NodeDependency
    {
        return new NodeDependency($this->populateNode($on), new NodePosition($on->getLine(), 0, ''), $type);
    }

    protected function findContext(AstNode $node): ?InputNode
    {
        $node = $this->parser->findContextNode($node);
        return $node ? $this->populateNode($node) : null;
    }

    protected function handleRef($node, $depType): void
    {
        $parent = $this->findContext($node);
        if ($parent) {
            $parent->addDependency($this->getDependency($node, $depType));
        }
    }
}
