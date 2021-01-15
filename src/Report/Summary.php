<?php
declare(strict_types=1);

namespace DepCheck\Report;


final class Summary
{
    public int $unknownElements;
    public int $violations;
    public int $allowedDependencies;

    public function __construct(int $unknownElements, int $violations, int $allowedDependencies)
    {
        $this->unknownElements = $unknownElements;
        $this->violations = $violations;
        $this->allowedDependencies = $allowedDependencies;
    }


}
