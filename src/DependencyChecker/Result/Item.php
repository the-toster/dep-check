<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Element;

final class Item
{
    private const ALLOWED = 0;
    private const VIOLATE = 1;
    private const UNKNOWN = 2;
    private int $status;

    private function __construct(int $status, Element $from, Element $to)
    {
        $this->status = $status;
    }

    public static function allowed(Element $from, Element $to): self
    {
        return new self(self::ALLOWED, $from, $to);
    }

    public static function violate(Element $from, Element $to): self
    {
        return new self(self::VIOLATE, $from, $to);
    }

    public static function unknown(Element $from, Element $to): self
    {
        return new self(self::UNKNOWN, $from, $to);
    }

    public function isViolate(): bool
    {
        return $this->status === self::VIOLATE;
    }

    public function isUnknown(): bool
    {
        return $this->status === self::UNKNOWN;
    }

    public function isAllowed(): bool
    {
        return $this->status === self::ALLOWED;
    }

}
