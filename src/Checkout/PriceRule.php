<?php

namespace Kata\Checkout;


use Kata\Checkout\Exception\PriceLowerThanOneException;
use Kata\Checkout\Exception\QuantityLowerThanOneException;

class PriceRule implements PriceRuleInterface
{
    /**
     * @var Item
     */
    private $item;

    /**
     * @var int
     */
    private $unitPrice;


    public function __construct(Item $item, int $unitPrice)
    {
        if ($unitPrice < 1) {
            throw PriceLowerThanOneException::create();
        }

        $this->item = $item;
        $this->unitPrice = $unitPrice;
    }

    public function isFor(Item $item): bool
    {
        return $this->item->equals($item);
    }

    public function equals(PriceRule $priceRule): bool
    {
        return $this->item->equals($priceRule->item);
    }

    public function getPrice(int $quantity): int
    {
        if ($quantity < 1) {
            throw QuantityLowerThanOneException::create();
        }

        return $this->unitPrice * $quantity;
    }
}
