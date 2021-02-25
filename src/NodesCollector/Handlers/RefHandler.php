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

    public function isSupported(string $type): bool
    {
        return isset($this->supportedTypes[$type]);
    }

    public function handle(Node $node): void
    {
        $type = get_class($node);
        if(!$this->isSupported($type)) {
            throw new \InvalidArgumentException('Unsupported node');
        }
        $depType = $this->supportedTypes[$type];

        $this->handleRef($node, $depType);
    }
}
