<?php

namespace Kata\Checkout\Tests;


use Kata\Checkout\Item;
use Kata\Checkout\SpecialPriceRule;
use PHPUnit\Framework\TestCase;

class SpecialPriceRuleTest extends TestCase
{
    /**
     * @param SpecialPriceRule $priceRule
     * @param int $quantity
     * @param int $expectedPrice
     * @dataProvider  dataProviderForTestGetPrice
     */
    public function testGetPrice(SpecialPriceRule $priceRule, int $quantity,  int $expectedPrice): void
    {
        self::assertEquals($expectedPrice, $priceRule->getPrice($quantity));
    }

    public function dataProviderForTestGetPrice(): array
    {
        return [
            [
                new SpecialPriceRule(new Item('A'), 10, 8, 2),
                1,
                10
            ],
            [
                new SpecialPriceRule(new Item('A'), 10, 8, 2),
                2,
                8
            ],
            [
                new SpecialPriceRule(new Item('A'), 10, 8, 2),
                3,
                18
            ],
            [
                new SpecialPriceRule(new Item('A'), 10, 8, 2),
                4,
                16
            ],
        ];
    }
}