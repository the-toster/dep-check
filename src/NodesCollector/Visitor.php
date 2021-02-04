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
//            var_dump($node->returnType);
        } else {
        }
    }

    private function handleFunctionDeclaration(\PhpParser\Node\Stmt\Function_ $node): void
    {
        $id = $this->getId($node->namespacedName);
        $checkNode = $this->getNode($id);

        foreach($node->params as $param) {
            /** @var \PhpParser\Node\Name\FullyQualified $paramNode */
            $paramNode = $this->getNode($this->getId($param));
            $position = new Position($param->getLine(), $param->getStartTokenPos());
            if($paramNode) {
                $node->addParamDependency($paramNode, $position);
            }
        }

        if(isset($node->returnType)) {
            $retNode = $this->getNode($this->getId($node->returnType));
            if($retNode) {
                $node->addReturnDependency($retNode, $position);
            }
            var_dump($node->returnType);
        }

        $this->nodes[$id] = $node;
    }

    private function getId(Node\Name $name): string
    {
        return implode('\\', $name->parts);
    }

    private function getNode(string $id): CheckNode
    {
        $node = isset($this->nodes[$id]) ? $this->nodes[$id] :
            new CheckNode($id, [], new Properties(''));

        return $node;
    }

    private function getDependency(Node\Name $name): NodeDependency
    {
        $id = $this->getId($name);
        $node = $this->getNode($id);
        $pos = new NodePosition($name->getLine(), $name->getStartTokenPos(), '');
        return new NodeDependency($node, $pos);
    }
}
