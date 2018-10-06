<?php

namespace Kata\Checkout;

use Kata\Checkout\Exception\PriceLowerThanOneException;
use Kata\Checkout\Exception\QuantityLowerThanOneException;

class SpecialPriceRule extends PriceRule
{
    /**
     * @var int
     */
    protected $specialPrice;

    /**
     * @var int
     */
    protected $specialQuantity;

    public function __construct(Item $item, int $unitPrice, int $specialPrice, int $specialQuantity)
    {
        parent::__construct($item, $unitPrice);

        if ($specialPrice < 1) {
            throw PriceLowerThanOneException::create();
        }

        if ($specialQuantity < 1) {
            throw QuantityLowerThanOneException::create();
        }

        $this->specialPrice = $specialPrice;
        $this->specialQuantity = $specialQuantity;
    }

    public function getPrice(int $quantity): int
    {
        if ($quantity < 1) {
            throw QuantityLowerThanOneException::create();
        }

        $unitPrice = 0;
        if ($quantity % $this->specialQuantity) {
            $unitPrice = parent::getPrice($quantity % $this->specialQuantity);
        }

        return (int) (floor($quantity / $this->specialQuantity)) * $this->specialPrice + $unitPrice;
    }
}