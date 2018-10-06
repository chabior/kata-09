<?php

namespace Kata\Checkout\Tests;


use Kata\Checkout\Exception\PriceLowerThanOneException;
use Kata\Checkout\Exception\QuantityLowerThanOneException;
use Kata\Checkout\Item;
use Kata\Checkout\PriceRule;
use PHPUnit\Framework\TestCase;

class PriceRuleTest extends TestCase
{
    public function testPriceValidation(): void
    {
        $this->expectException(PriceLowerThanOneException::class);
        new PriceRule(new Item('A'), 0);
    }

    public function testIsFor(): void
    {
        $priceRule = new PriceRule(new Item('A'), 1);
        self::assertTrue($priceRule->isFor(new Item('A')));
        self::assertFalse($priceRule->isFor(new Item('B')));
    }

    public function testEquals(): void
    {
        $priceRule = new PriceRule(new Item('A'), 1);
        self::assertTrue($priceRule->equals(new PriceRule(new Item('A'), 2)));
        self::assertTrue($priceRule->equals(new PriceRule(new Item('A'), 1)));
        self::assertFalse($priceRule->equals(new PriceRule(new Item('B'), 1)));
    }

    /**
     * @param PriceRule $priceRule
     * @param int $quantity
     * @param int $expectedPrice
     * @dataProvider  dataProviderForTestGetPrice
     */
    public function testGetPrice(PriceRule $priceRule, int $quantity,  int $expectedPrice): void
    {
        self::assertEquals($expectedPrice, $priceRule->getPrice($quantity));
    }

    public function dataProviderForTestGetPrice(): array
    {
        return [
            [
                new PriceRule(new Item('A'), 1),
                1,
                1
            ],
            [
                new PriceRule(new Item('A'), 40),
                2,
                80
            ],
            [
                new PriceRule(new Item('A'), 10),
                5,
                50
            ],
        ];
    }

    public function testPriceWithLowerQuantity()
    {
        $this->expectException(QuantityLowerThanOneException::class);
        (new PriceRule(new Item('A'), 10))->getPrice(0);
    }
}