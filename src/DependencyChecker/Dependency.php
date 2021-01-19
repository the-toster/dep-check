<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


final class Dependency
{
    public Element $on;
    public Position $position;

    public function __construct(Element $on, Position $position)
    {
        $this->on = $on;
        $this->position = $position;
    }

}
