<?php

declare(strict_types=1);

namespace DepCheck\IO;


use DepCheck\Model\Input\Properties;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;

final class Visitor extends NodeVisitorAbstract
{
    public array $nodes = [];
    private array $tokens;

    public function setTokens(array $tokens) {
        $this->tokens = $tokens;
    }

    /**
     * Collect nodes from AST: declarations (classes, interfaces, traits, functions, constants), calls, type hints, use directives.
     */


    public function leaveNode(Node $node) {
        if ($node instanceof PhpParser\Node\Stmt\Property) {
            $this->nodes[] = new \DepCheck\Model\Input\Node('', [], new Properties(''));
        }
    }
}
