<?php
declare(strict_types=1);

namespace DepCheck\Model\LayerAssignmentService;


use DepCheck\Model\DependencyChecker\Element;
use DepCheck\Model\DependencyChecker\Layer;
use DepCheck\Model\Input\Node;

interface AssignmentServiceInterface
{
    /**
     * @param Layer[] $layers
     * @param Node[] $nodes
     * @return Element[]
     */
    public function assign(array $layers, array $nodes): array;
}
