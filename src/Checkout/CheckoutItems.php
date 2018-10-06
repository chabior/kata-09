<?php

namespace Kata\Checkout;


class CheckoutItems
{
    /**
     * @var CheckoutItem[]
     */
    private $items = [];

    public function add(Item $item): CheckoutItems
    {
        $sameItemsInCheckout = $this->find($this->forItem($item));
        $items = $this->items;

        if ($sameItemsInCheckout->isEmpty()) {
            $items[] = CheckoutItem::create($item);
        } else {
            $item = $sameItemsInCheckout->first()->increaseQuantity();
            foreach ($items as $key => $checkoutItem) {
                if ($item->equals($checkoutItem)) {
                    unset($items[$key]);
                    $items[] = $item;
                }
            }
        }

        return $this->createNew($items);
    }

    public function total(PriceRules $priceRules): int
    {
        return array_reduce($this->items, function (int $total, CheckoutItem $checkoutItem) use($priceRules) {
            return $total + $checkoutItem->getPrice($priceRules);
        }, 0);
    }

    private function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    private function first(): ?CheckoutItem
    {
        return current($this->items);
    }

    private function forItem(Item $item):callable 
    {
        return function (CheckoutItem $checkoutItem) use($item) {
            return $checkoutItem->isFor($item);
        };
    }

    private function find(\Closure $callback): CheckoutItems
    {
        return $this->createNew(array_filter($this->items, $callback));
    }
    
    private function createNew(array $items): CheckoutItems
    {
        $newCheckoutItems = clone $this;
        $newCheckoutItems->items = array_values($items);
        return $newCheckoutItems;
    }
}