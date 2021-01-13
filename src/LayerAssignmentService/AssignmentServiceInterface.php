<?php
declare(strict_types=1);

namespace DepCheck\LayerAssignmentService;


use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Layer;
use DepCheck\Input\Node;

interface AssignmentServiceInterface
{
    /**
     * @param Layer[] $layers
     * @param Node[] $nodes
     * @return Element[]
     */
    public function assign(array $layers, array $nodes): array;
}
