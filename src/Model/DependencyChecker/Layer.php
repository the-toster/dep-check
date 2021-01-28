<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker;


final class Layer
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

}
