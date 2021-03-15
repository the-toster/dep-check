<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\DocBlock;


final class TypeName
{
    public string $name;
    public bool $resolved;

    public function __construct(string $name, bool $resolved)
    {
        $this->name = $name;
        $this->resolved = $resolved;
    }

    public static function resolved(string $name): self
    {
        return ;
    }
}
