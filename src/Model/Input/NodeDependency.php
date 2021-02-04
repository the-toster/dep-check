<?php

declare(strict_types=1);

namespace DepCheck\Model\Input;


final class NodeDependency
{
    public Node $on;
    public NodePosition $pos;

    public function __construct(Node $on, NodePosition $pos)
    {
        $this->on = $on;
        $this->pos = $pos;
    }

}
