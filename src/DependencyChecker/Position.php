<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


final class Position
{
    public int $row;
    public int $col;

    public function __construct(int $row, int $col)
    {
        $this->row = $row;
        $this->col = $col;
    }

}
