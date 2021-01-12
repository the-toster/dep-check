<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


final class Dependency
{
    public Element $on;

    public function __construct(Element $on)
    {
        $this->on = $on;
    }

}
