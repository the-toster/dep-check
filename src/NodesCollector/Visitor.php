<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Position;
use DepCheck\Model\Input\Node as CheckNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;

final class Visitor extends NodeVisitorAbstract
{
    private array $nodes = [];
    private array $tokens;

    public function getCollectedNodes(): array
    {
        return $this->nodes;
    }

    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Collect nodes from AST:
     *  node types:
     *  - class
     *  - trait
     *  - interface
     *  - function
     *  - constant
     *
     *  ref types:
     *  - extends
     *  - implements
     *  - use
     *  - use trait
     *  - call method / function
     *  - read constant
     *  - param type hint
     *  - property type hint
     *  - return type hint
     *  - docblock type hint
     */
    public function leaveNode(Node $node)
    {

        if ($node instanceof \PhpParser\Node\Stmt\Function_) {
            $this->handleFunctionDeclaration($node);
        } else {

        }
    }

    private function handleFunctionDeclaration(\PhpParser\Node\Stmt\Function_ $node): void
    {
        $id = $this->getId($node->namespacedName);
        $checkNode = $this->populateNode($id);

        foreach($node->params as $param) {
            /** @var Node\Param $param */
            $paramDep = $this->getDependency($param->type, NodeDependency::PARAM);
            $checkNode->addDependency($paramDep);
        }

        if(isset($node->returnType)) {
            $retDep = $this->getDependency($node->returnType, NodeDependency::RETURN);
            $checkNode->addDependency($retDep);
        }

    }

    private function getId(Node\Name $name): string
    {
        return implode('\\', $name->parts);
    }

    private function populateNode(string $id): CheckNode
    {
        if(isset($this->nodes[$id])) {
            return $this->nodes[$id];
        }
        return $this->nodes[$id] = new CheckNode($id, [], new Properties(''));
    }

    private function getDependency(Node\Name $name, int $type): NodeDependency
    {
        $id = $this->getId($name);
        $node = $this->populateNode($id);
        var_dump($name);
        $pos = new NodePosition($name->getLine(), 0, '');
        return new NodeDependency($node, $pos, $type);
    }
}
