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
        if(strtolower($constFetch->name->toString()) === 'null') {
            return;
        }

        $this->handleRef($constFetch, NodeDependency::GLOBAL_CONST);
    }
}
