<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;


use DepCheck\Model\Input\NodeDependency;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;

final class ClassRefHandler extends AbstractHandler
{
    private array $supportedTypes = [
        ClassConstFetch::class => NodeDependency::CLASS_CONST,
        Node\Expr\StaticCall::class => NodeDependency::CALL,
        Node\Expr\StaticPropertyFetch::class => NodeDependency::PROPERTY,
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

        $this->handleRef($node->class, $depType);
    }
}
