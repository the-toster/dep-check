<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker;


final class Rules
{
    private array $rules = [];

    public function add(string $from, string $to): void
    {
        $this->rules[$from][$to] = true;
    }

    public function has(string $from, string $to): bool
    {
        return isset($this->rules[$from][$to]);
    }
}
