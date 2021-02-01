<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


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
     * Collect nodes from AST: declarations (classes, interfaces, traits, functions, constants), calls, type hints, use directives.
     */
    public function leaveNode(Node $node)
    {

        if ($node instanceof \PhpParser\Node\Stmt\Function_) {
//            $this->handleFunctionDeclaration($node);
            var_dump($node->returnType);

        } else {
        }
    }

    private function handleFunctionDeclaration(\PhpParser\Node\Stmt\Function_ $node): void
    {
        $id = $this->getId($node);
        $node = isset($this->nodes[$id]) ? $this->nodes[$id] : new \DepCheck\Model\Input\Node($id, [], new Properties(''));

        foreach($node->params as $param) {
            $paramNode = $this->getParamNode($param);
            if($paramNode) {
                $node->addParamDependency($paramNode, $position);
            }
        }

        if(isset($node->returnType)) {
            $retNode = $this->getReturnNode($param);
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

    private function getParamNode(\PhpParser\Node\Name\FullyQualified $param)
    {
        $id = $this->getId($param);
        if($)
    }
}
