<?php
declare(strict_types=1);

namespace Tests\DependencyChecker;

use DepCheck\DependencyChecker\Checker;
use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Rules;
use PHPUnit\Framework\TestCase;

class CheckerTest extends TestCase
{

    public function testCheck()
    {
        $rules = new Rules();
        $checker = new Checker($rules);

        $elements = [new Element('id', [], [])];

        $result = $checker->check($elements);

        $this->assertEquals(1, $result->unknown);
        $this->assertEquals(0, $result->allowed);
        $this->assertEquals(0, $result->violate);

    }
}
