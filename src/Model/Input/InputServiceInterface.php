<?php
declare(strict_types=1);

namespace DepCheck\Model\Input;


use DepCheck\Model\DependencyChecker\Element;

interface InputServiceInterface
{
    /**
     * @return Node[]
     */
    public function getNodes(): array;
}
