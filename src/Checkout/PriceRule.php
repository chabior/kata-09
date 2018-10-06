<?php

namespace Kata\Checkout;


class PriceRule
{
    /**
     * @var Item
     */
    protected $item;

    /**
     * @var int
     */
    protected $unitPrice;

    /**
     * @var int
     */
    protected $specialPrice;

    /**
     * @var int
     */
    protected $specialQuantity;

    /**
     * PriceRule constructor.
     * @param Item $item
     * @param int $unitPrice
     * @param int $specialPrice
     * @param int $specialQuantity
     */
    public function __construct(Item $item, int $unitPrice, int $specialPrice = null, int $specialQuantity = null)
    {
        $this->item = $item;
        $this->unitPrice = $unitPrice;
        $this->specialPrice = $specialPrice;
        $this->specialQuantity = $specialQuantity;
    }

    public function isFor(Item $item): bool
    {
        return $this->item->equals($item);
    }

    public function getPrice(int $quantity): int
    {
        if ($quantity < 1) {
            throw new \RuntimeException("Quantity cant be lower than 1");
        }

        if (empty($this->specialPrice)) {
            return $this->unitPrice * $quantity;
        }

        if ($quantity === $this->specialQuantity) {
            return $this->specialPrice;
        }

        if ($quantity < $this->specialQuantity) {
            return $quantity * $this->unitPrice;
        }

        if ($quantity % $this->specialQuantity === 0) {
            return ($quantity / $this->specialQuantity) * $this->specialPrice;
        }

        return (floor($quantity / $this->specialQuantity)) * $this->specialPrice + (($quantity % $this->specialQuantity) * $this->unitPrice);
    }

}