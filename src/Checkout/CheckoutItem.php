<?php

namespace Kata\Checkout;


class CheckoutItem
{
    /**
     * @var Item
     */
    private $item;

    /**
     * @var int
     */
    private $quantity;

    /**
     * CheckoutItem constructor.
     * @param Item $item
     * @param int $quantity
     */
    public function __construct(Item $item, $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
    }

    public static function create(Item $item): CheckoutItem
    {
        return new self($item, 1);
    }

    public function isFor(Item $item): bool
    {
        return $this->item->equals($item);
    }

    public function increaseQuantity(): CheckoutItem
    {
        return new self($this->item, $this->quantity + 1);
    }

    public function getPrice(PriceRules $priceRules): int
    {
        return $priceRules->getPrice($this->item, $this->quantity);
    }

    public function equals(CheckoutItem $checkoutItem)
    {
        return $this->item->equals($checkoutItem->item);
    }
}