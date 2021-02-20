<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;

final class RefHandler extends AbstractHandler
{
    private array $supportedTypes = [
        ClassConstFetch::class => NodeDependency::CLASS_CONST,
        New_::class => NodeDependency::INSTANTIATE,
        FuncCall::class => NodeDependency::CALL,
        ConstFetch::class => NodeDependency::GLOBAL_CONST,
    ];

    public function isSupported(Node $node): bool
    {
        return isset($this->supportedTypes[get_class($node)]);
    }

    public function handle(Node $node): void
    {
        if(!$this->isSupported($node)) {
            throw new \InvalidArgumentException('Unsupported node');
        }
        $depType = $this->supportedTypes[get_class($node)];

        $this->handleRef($node, $depType);
    }
}
