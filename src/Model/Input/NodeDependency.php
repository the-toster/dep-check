<?php

declare(strict_types=1);

namespace DepCheck\Model\Input;


final class NodeDependency
{
    public Node $on;
    public NodePosition $pos;

    public const PARAM          = 0;
    public const RETURN         = 1;
    public const CALL           = 2;
    public const PROPERTY       = 3;
    public const GLOBAL_CONST   = 4;
    public const CLASS_CONST    = 5;
    public const EXTENDS        = 6;
    public const IMPLEMENTS     = 7;
    public const INSTANTIATE    = 8;
    public const EXTENDS_INTERFACE    = 9;
    public const USES_TRAIT    = 10;

    public function __construct(Node $on, NodePosition $pos, int $type)
    {
        $this->on = $on;
        $this->pos = $pos;
        $this->type = $type;
    }

}
