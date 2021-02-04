<?php

declare(strict_types=1);

namespace DepCheck\Model\Input;


final class NodePosition
{
    public int $line;
    public int $col;
    public string $path;

    public function __construct(int $line, int $col, string $path)
    {
        $this->line = $line;
        $this->col = $col;
        $this->path = $path;
    }


}
