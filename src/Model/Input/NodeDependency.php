<?php

declare(strict_types=1);

namespace DepCheck\Model\Input;


final class NodeDependency
{
    public Node $on;
    public NodePosition $pos;

    public const PARAM  = 0;
    public const RETURN = 1;
    public const CALL   = 2;

    public function __construct(Node $on, NodePosition $pos, int $type)
    {
        $this->on = $on;
        $this->pos = $pos;
        $this->type = $type;
    }

}
