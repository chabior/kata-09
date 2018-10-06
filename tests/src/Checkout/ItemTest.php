<?php

namespace Kata\Checkout\Tests;


use Kata\Checkout\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testEquals()
    {
        self::assertTrue((new Item('A'))->equals(new Item('A')));
        self::assertFalse((new Item('A'))->equals(new Item('B')));
    }
}