<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use PhpParser\Node\Stmt\Class_;

final class ClassDeclaration extends AbstractHandler
{
    public function handle(Class_ $node): void
    {
        $id = $this->getId($node->namespacedName);
        $this->populateNode($id);
    }
}
