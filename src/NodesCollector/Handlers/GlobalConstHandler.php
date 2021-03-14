<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;

final class GlobalConstHandler extends AbstractHandler
{
    public function handle(ConstFetch $constFetch): void
    {
        $probe = strtolower($constFetch->name->toString());
        if(in_array($probe, ['null', 'false', 'true', 'static', 'self'])) {
            return;
        }

        $this->handleRef($constFetch, NodeDependency::GLOBAL_CONST);
    }
}
