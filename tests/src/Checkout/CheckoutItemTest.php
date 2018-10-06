<?php

namespace Kata\Checkout\Tests;


use Kata\Checkout\CheckoutItem;
use Kata\Checkout\Exception\QuantityLowerThanOneException;
use Kata\Checkout\Item;
use PHPUnit\Framework\TestCase;

class CheckoutItemTest extends TestCase
{
    /**
     * @param int $quantity
     *
     * @dataProvider dataProviderForTestQuantityValidation
     */
    public function testQuantityValidation(int $quantity): void
    {
        $this->expectException(QuantityLowerThanOneException::class);

        new CheckoutItem(new Item('A'), $quantity);
    }

    public function dataProviderForTestQuantityValidation(): array
    {
        return [[0], [-1]];
    }

    public function testIsFor(): void
    {
        $checkoutItem = new CheckoutItem(new Item('A'), 1);

        self::assertTrue($checkoutItem->isFor(new Item('A')));
        self::assertFalse($checkoutItem->isFor(new Item('B')));
    }

    public function testIncreaseQuantity(): void
    {
        $checkoutItem = new CheckoutItem(new Item('A'), 1);
        $checkoutItem = $checkoutItem->increaseQuantity();

        self::assertEquals(2, $checkoutItem->quantity());

        $checkoutItem = $checkoutItem->increaseQuantity();
        self::assertEquals(3, $checkoutItem->quantity());
    }

    public function testEquals(): void
    {
        $checkoutItem = new CheckoutItem(new Item('A'), 1);

        self::assertTrue($checkoutItem->equals(new CheckoutItem(new Item('A'), 1)));

        self::assertFalse($checkoutItem->equals(new CheckoutItem(new Item('B'), 1)));
    }
}