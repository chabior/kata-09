<?php

namespace Kata\Checkout\Tests;


use Kata\Checkout\CheckoutItem;
use Kata\Checkout\CheckoutItems;
use Kata\Checkout\Item;
use PHPUnit\Framework\TestCase;

class CheckoutItemsTest extends TestCase
{
    public function testAdd()
    {
        $checkoutItems = new CheckoutItems();
        $checkoutItems = $checkoutItems->add(new Item('A'));

        self::assertTrue($checkoutItems->first()->equals(new CheckoutItem(new Item('A'), 1)));
        self::assertTrue($checkoutItems->first()->hasQuantity(1));

        $checkoutItems = $checkoutItems->add(new Item('A'));
        self::assertTrue($checkoutItems->first()->equals(new CheckoutItem(new Item('A'), 2)));
        self::assertTrue($checkoutItems->first()->hasQuantity(2));

        $checkoutItems = $checkoutItems->add(new Item('B'));
        self::assertTrue($checkoutItems->find(function (CheckoutItem $checkoutItem) {
            return $checkoutItem->isFor(new Item('B'));
        })->first()->hasQuantity(1));
    }

    public function testIsEmpty()
    {
        $checkoutItems = new CheckoutItems();
        self::assertTrue($checkoutItems->isEmpty());

        $checkoutItems = $checkoutItems->add(new Item('A'));
        self::assertFalse($checkoutItems->isEmpty());
    }

    public function testFirst()
    {
        $checkoutItems = new CheckoutItems();
        self::assertNull($checkoutItems->first());

        $checkoutItems = $checkoutItems->add(new Item('A'));
        self::assertTrue($checkoutItems->first()->isFor(new Item('A')));
    }
}