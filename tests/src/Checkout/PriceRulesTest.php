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
}