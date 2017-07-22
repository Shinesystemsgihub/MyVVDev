<?php

use PHPUnit\Framework\TestCase;

class DependencyFailureTest extends TestCase
{
    public function testZero()
    {
        $this->assertTrue(true);
    }

    public function testOne()
    {
        $this->assertTrue(false);
    }

    /**
     * @depends testOne
     */
    public function testTwo()
    {
    }
}
