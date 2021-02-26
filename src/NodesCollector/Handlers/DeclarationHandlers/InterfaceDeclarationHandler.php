<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node\Stmt\Interface_;

final class InterfaceDeclarationHandler extends \DepCheck\NodesCollector\Handlers\AbstractHandler
{
    public function handle(Interface_ $node): void
    {
        $interface = $this->populateNode($node->namespacedName);

        foreach ($node->extends as $extend) {
            $this->handleTypeOccurrence($extend, $interface, NodeDependency::EXTENDS_INTERFACE);
        }
    }
}
