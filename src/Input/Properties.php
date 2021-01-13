<?php
declare(strict_types=1);

namespace DepCheck\Input;


final class Properties
{
    public string $annotation;

    public function __construct(string $annotation)
    {
        $this->annotation = $annotation;
    }

}
