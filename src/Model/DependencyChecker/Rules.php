<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker;


final class Rules
{
    private array $rules = [];

    public function add(Layer $from, Layer $to): void
    {
        $this->rules[$from->name][$to->name] = true;
    }

    public function has(Layer $from, Layer $to): bool
    {
        return isset($this->rules[$from->name][$to->name]);
    }
}
