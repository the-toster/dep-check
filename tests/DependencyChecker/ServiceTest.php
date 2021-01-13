<?php
declare(strict_types=1);

namespace Tests\DependencyChecker;

use DepCheck\DependencyChecker\Service;
use DepCheck\DependencyChecker\Element;
use DepCheck\DependencyChecker\Rules;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{

    public function testCheck()
    {
        $rules = new Rules();
        $checker = new Service($rules);

        $elements = [new Element('id', [], [])];

        $result = $checker->check($elements);

        $this->assertEquals(1, $result->total);

    }
}
