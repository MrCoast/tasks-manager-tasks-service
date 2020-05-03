<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class HelloTest extends TestCase
{
    public function testAdd()
    {
        $this->assertEquals(42, 41 + 1);
    }
}
