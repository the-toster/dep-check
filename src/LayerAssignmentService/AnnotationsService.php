<?php
declare(strict_types=1);

namespace DepCheck\LayerAssignmentService;


use DepCheck\DependencyChecker\Dependency;
use DepCheck\DependencyChecker\Element;
use DepCheck\Input\Node;

final class AnnotationsService implements AssignmentServiceInterface
{

    /**
     * @inheritDoc
     */
    public function assign(array $layers, array $nodes): array
    {
        $layerSet = [];
        foreach ($layers as $layer) {
            $layerSet[$layer->name] = $layer;
        }

        $r = [];
        foreach ($nodes as $node) {
            if(isset($layerSet[$node->props->annotation])) {
                $elLayers = [$layerSet[$node->props->annotation]];
            }
            $r[$node->id] = new Element($node->id, $elLayers, []);
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
}
