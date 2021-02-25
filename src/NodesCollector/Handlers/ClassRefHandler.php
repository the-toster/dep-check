<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;

final class ClassRefHandler extends AbstractHandler
{
    private array $supportedTypes = [
        ClassConstFetch::class => NodeDependency::CLASS_CONST,
        StaticCall::class => NodeDependency::CALL,
        StaticPropertyFetch::class => NodeDependency::PROPERTY,
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

        $this->handleRef($node->class, $depType);
    }
}
