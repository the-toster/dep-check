<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker;


final class Element
{
    public string $id;

    public ?Layer $layer;

    /** @var Dependency[] $dependencies */
    public array $dependencies;

    public function __construct(string $id, ?Layer $layer, array $dependencies)
    {
        $this->id = $id;
        $this->layer = $layer;
        $this->dependencies = $dependencies;
    }

    public function layerUnknown(): bool
    {
        return is_null($this->layer);
    }

    public function hasLayer(): bool
    {
        return !$this->layerUnknown();
    }
}
