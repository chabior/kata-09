<?php

namespace Kata\Checkout\Tests;

use Kata\Checkout\Checkout;
use Kata\Checkout\Item;
use Kata\Checkout\PriceRule;
use Kata\Checkout\PriceRules;
use Kata\Checkout\SpecialPriceRule;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
    public function testTotals()
    {
        self::assertEquals(  0, $this->price(""));
        self::assertEquals( 50, $this->price("A"));
        self::assertEquals( 80, $this->price("AB"));
        self::assertEquals(115, $this->price("CDBA"));

        self::assertEquals(100, $this->price("AA"));
        self::assertEquals(130, $this->price("AAA"));
        self::assertEquals(180, $this->price("AAAA"));
        self::assertEquals(230, $this->price("AAAAA"));
        self::assertEquals(260, $this->price("AAAAAA"));

        self::assertEquals(160, $this->price("AAAB"));
        self::assertEquals(175, $this->price("AAABB"));
        self::assertEquals(190, $this->price("AAABBD"));
        self::assertEquals(190, $this->price("DABABA"));
    }

    public function testIncremental()
    {
        $checkout = $this->createCheckout();
        self::assertEquals(0, $checkout->total());

        $checkout = $checkout->scan(new Item('A'));
        self::assertEquals(50, $checkout->total());

        $checkout = $checkout->scan(new Item('B'));
        self::assertEquals(80, $checkout->total());

        $checkout = $checkout->scan(new Item('A'));
        self::assertEquals(130, $checkout->total());

        $checkout = $checkout->scan(new Item('A'));
        self::assertEquals(160, $checkout->total());

        $checkout = $checkout->scan(new Item('B'));
        self::assertEquals(175, $checkout->total());
    }

    protected function price(string $items): int
    {
        $checkout = $this->createCheckout();

        foreach (str_split($items) as $itemName) {
            if (!empty($itemName)) {
                $checkout = $checkout->scan(new Item($itemName));
            }
        }

        return $checkout->total();
    }

    protected function createCheckout():Checkout
    {
        $rules = (new PriceRules())
            ->addPriceRule(new SpecialPriceRule(new Item('A'), 50, 130, 3))
            ->addPriceRule(new SpecialPriceRule(new Item('B'), 30, 45, 2))
            ->addPriceRule(new PriceRule(new Item('C'), 20))
            ->addPriceRule(new PriceRule(new Item('D'), 15))
        ;
        $checkout = new Checkout($rules);

        return $checkout;
    }
}