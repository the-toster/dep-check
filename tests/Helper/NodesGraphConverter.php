<?php

declare(strict_types=1);

namespace Tests\Helper;


use DepCheck\Model\Input\Node;

final class NodesGraphConverter
{
    /**
     * @param Node[] $nodes
     * @return array
     */
    public function toIds(array $nodes): array
    {
        $r = [];
        foreach ($nodes as $node) {
            $r[$node->id] = [];
            foreach ($node->depends as $dependency) {
                $r[$node->id][] = $dependency->on->id;
            }
        }

        return $r;
    }
}
