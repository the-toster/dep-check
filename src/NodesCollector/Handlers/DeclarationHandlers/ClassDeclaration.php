<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;


use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node\Stmt\Class_;

final class ClassDeclaration extends AbstractHandler
{
    public function handle(Class_ $node): void
    {
        $class = $this->populateNode($node);
        if($node->extends) {
            $class->addDependency($this->getDependency($node->extends, NodeDependency::EXTENDS));
        }

        foreach ($node->implements as $interface) {
            $class->addDependency($this->getDependency($interface, NodeDependency::IMPLEMENTS));
        }
    }
}
