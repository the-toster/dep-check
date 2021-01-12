<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


final class Element
{
    public string $id;

    /** @var Layer[] $layers */
    public array $layers;

    /** @var Dependency[] $dependencies */
    public array $dependencies;

    public function __construct(string $id, array $layers, array $dependencies)
    {
        $this->id = $id;
        $this->layers = $layers;
        $this->dependencies = $dependencies;
    }

    public function layerUnknown(): bool
    {
        return count($this->layers) === 0;
    }
}
