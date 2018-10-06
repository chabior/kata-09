<?php

namespace Kata\Checkout;


class CheckoutItems
{
    /**
     * @var CheckoutItem[]
     */
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add(Item $item): CheckoutItems
    {
        $checkoutItems = $this->find($this->forItem($item));
        $items = $this->items;

        if ($checkoutItems->isEmpty()) {
            $items[] = CheckoutItem::create($item);
        } else {
            $item = $checkoutItems->first()->increaseQuantity();
            foreach ($items as $key => $checkoutItem) {
                if ($item->equals($checkoutItem)) {
                    unset($items[$key]);
                    $items[] = $item;
                }
            }
        }

        return new self(array_values($items));
    }

    public function total(PriceRules $priceRules): int
    {
        return array_reduce($this->items, function (int $total, CheckoutItem $checkoutItem) use($priceRules) {
            return $total + $checkoutItem->getPrice($priceRules);
        }, 0);
    }

    protected function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    protected function first(): ?CheckoutItem
    {
        return current($this->items);
    }

    protected function forItem(Item $item):\Closure
    {
        return function (CheckoutItem $checkoutItem) use($item) {
            return $checkoutItem->isFor($item);
        };
    }

    protected function forCheckoutItem(CheckoutItem $checkoutItem):\Closure
    {
        return function (CheckoutItem $item) use ($checkoutItem) {
            return $item->equals($checkoutItem);
        };
    }

    protected function find(\Closure $callback): CheckoutItems
    {
        return new CheckoutItems(array_filter($this->items, $callback));
    }
}