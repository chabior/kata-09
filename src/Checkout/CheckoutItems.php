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
        $checkoutItem = $this->find($this->forItem($item))->first();
        $items = $this->items;

        if ($checkoutItem) {
            $item = $checkoutItem->increaseQuantity();
            foreach ($items as $key => $checkoutItem) {
                if ($item->equals($checkoutItem)) {
                    unset($items[$key]);
                    $items[] = $item;
                }
            }
        } else {
            $items[] = CheckoutItem::create($item);
        }

        return $this->createNew($items);
    }

    public function total(PriceRules $priceRules): int
    {
        return array_reduce(
            $this->items, function (int $total, CheckoutItem $checkoutItem) use ($priceRules) {
                return $total + $checkoutItem->getPrice($priceRules);
            }, 0
        );
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    public function first(): ?CheckoutItem
    {
        $item = current($this->items);
        return $item ?: null;
    }

    public function find(callable $callback): CheckoutItems
    {
        return $this->createNew(array_filter($this->items, $callback));
    }

    private function forItem(Item $item): callable
    {
        return function (CheckoutItem $checkoutItem) use ($item) {
            return $checkoutItem->isFor($item);
        };
    }
    
    private function createNew(array $items): CheckoutItems
    {
        $newCheckoutItems = clone $this;
        $newCheckoutItems->items = array_values($items);
        return $newCheckoutItems;
    }
}
