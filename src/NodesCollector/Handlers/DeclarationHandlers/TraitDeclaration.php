<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers\DeclarationHandlers;


use DepCheck\Model\Input\NodeDependency;
use DepCheck\NodesCollector\Handlers\AbstractHandler;
use PhpParser\Node\Stmt\Trait_;

final class TraitDeclaration extends AbstractHandler
{
    public function handle(Trait_ $node): void
    {
        $this->populateNode($node);
    }
}
