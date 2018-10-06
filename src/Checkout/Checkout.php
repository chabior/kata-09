<?php

namespace Kata\Checkout;

class Checkout
{
    /**
     * @var PriceRules
     */
    private $priceRules;

    /**
     * @var CheckoutItems
     */
    private $items;

    /**
     * @param PriceRules $priceRules
     */
    public function __construct(PriceRules $priceRules)
    {
        $this->priceRules = $priceRules;
        $this->items = new CheckoutItems();
    }

    public function scan($item): Checkout
    {
        $items = $this->items->add($item);

        $checkout = clone $this;
        $checkout->items = $items;

        return $checkout;
    }

    public function total(): int
    {
        return $this->items->total($this->priceRules);
    }
}