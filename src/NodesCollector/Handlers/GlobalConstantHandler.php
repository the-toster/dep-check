<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use PhpParser\Node\Const_;

final class GlobalConstantHandler extends AbstractHandler
{
    public function handle(Const_ $node): void
    {
        $constant = $this->populateNode($this->getId($node->namespacedName));
        if($this->isGlobalConstant($node)) {
            //add parent dependency on this constant
        } else {
            //add parent dependency on constant class
        }
    }
}
