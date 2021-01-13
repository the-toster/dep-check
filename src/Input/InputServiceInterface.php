<?php
declare(strict_types=1);

namespace DepCheck\Input;


use DepCheck\DependencyChecker\Element;

interface InputServiceInterface
{
    /**
     * @return Node[]
     */
    public function getNodes(): array;
}
