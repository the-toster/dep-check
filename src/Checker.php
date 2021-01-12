<?php
declare(strict_types=1);

namespace DepCheck;


final class Checker
{
    public function check(): Report
    {
        return new Report;
    }
}