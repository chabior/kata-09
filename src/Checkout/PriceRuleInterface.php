<?php

namespace Kata\Checkout;


interface PriceRuleInterface
{
    public function getPrice(int $quantity): int;

    public function isFor(Item $item): bool;

    public function equals(PriceRule $priceRule): bool;
}