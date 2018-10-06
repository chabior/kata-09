<?php

namespace Kata\Checkout\Tests;


use Kata\Checkout\Exception\NoPriceRuleForItemException;
use Kata\Checkout\Exception\NotUniquePriceRulesException;
use Kata\Checkout\Item;
use Kata\Checkout\PriceRule;
use Kata\Checkout\PriceRules;
use Kata\Checkout\SpecialPriceRule;
use PHPUnit\Framework\TestCase;

class PriceRulesTest extends TestCase
{
    public function testUniquenessOfPriceRules(): void
    {
        $this->expectException(NotUniquePriceRulesException::class);

        new PriceRules([
            new PriceRule(new Item('A'), 10),
            new SpecialPriceRule(new Item('A'), 10, 3, 20)
        ]);
    }

    public function testGetPriceForMissingItem(): void
    {
        $this->expectException(NoPriceRuleForItemException::class);

        $priceRules = new PriceRules([
            new PriceRule(new Item('A'), 2)
        ]);

        $priceRules->getPrice(new Item('C'), 1);
    }

    public function testGetPrice(): void
    {
        $priceRules = new PriceRules([
            new PriceRule(new Item('A'), 2),
            new PriceRule(new Item('B'), 4),
            new SpecialPriceRule(new Item('C'), 10, 5, 2)
        ]);

        self::assertEquals(16, $priceRules->getPrice(new Item('B'), 4));
        self::assertEquals(8, $priceRules->getPrice(new Item('A'), 4));
        self::assertEquals(10, $priceRules->getPrice(new Item('C'), 1));
        self::assertEquals(5, $priceRules->getPrice(new Item('C'), 2));
        self::assertEquals(15, $priceRules->getPrice(new Item('C'), 3));
        self::assertEquals(10, $priceRules->getPrice(new Item('C'), 4));
    }
}