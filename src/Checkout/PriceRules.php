<?php

namespace Kata\Checkout;

class PriceRules
{
    /**
     * @var PriceRule[]
     */
    protected $rules = [];

    /**
     * PriceRules constructor.
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function getPrice(Item $item, int $quantity): int
    {
        $rules = $this->find($this->forItem($item));

        if ($rules->isEmpty()) {
            throw new \RuntimeException('There is no price for item ' . $item->toString());
        }

        return $rules->first()->getPrice($quantity);
    }

    public function isEmpty(): bool
    {
        return count($this->rules) === 0;
    }

    protected function first(): ?PriceRule
    {
        return $this->rules[0];
    }

    protected function forItem(Item $item):\Closure
    {
        return function (PriceRule $ruleItem) use($item) {
            return $ruleItem->isFor($item);
        };
    }

    protected function find(\Closure $callback): PriceRules
    {
        return new PriceRules(array_values(array_filter($this->rules, $callback)));
    }
}