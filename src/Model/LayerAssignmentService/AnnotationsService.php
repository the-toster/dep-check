<?php
declare(strict_types=1);

namespace DepCheck\Model\LayerAssignmentService;


use DepCheck\Model\DependencyChecker\Dependency;
use DepCheck\Model\DependencyChecker\Element;

final class AnnotationsService implements AssignmentServiceInterface
{

    /**
     * @inheritDoc
     */
    public function assign(array $layers, array $nodes): array
    {
        $layersMap = $this->mapLayersByName($layers);

        $r = [];
        // for every node create element with or without layer
        foreach ($nodes as $node) {
            $r[$node->id] = new Element($node->id, $layersMap[$node->props->annotation] ?? null, []);
        }

        foreach ($nodes as $node) {
            $deps = [];
            foreach ($node->depends as $depend) {
                $deps[] = new Dependency($r[$depend]);
            }

            $r[$node->id]->dependencies = $deps;
        }

        return $r;
    }

    private function mapLayersByName(array $layers): array {
        $layersMap = [];
        foreach ($layers as $layer) {
            $layersMap[$layer->name] = $layer;
        }

        return $layersMap;
    }
}
