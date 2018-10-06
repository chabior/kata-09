<?php

namespace Kata\Checkout;

use Kata\Checkout\Exception\NoPriceRuleForItemException;
use Kata\Checkout\Exception\NotUniquePriceRulesException;
use Kata\Checkout\Exception\QuantityLowerThanOneException;

class PriceRules
{
    /**
     * @var PriceRule[]
     */
    private $rules = [];

    public function __construct(array $rules)
    {
        $this->rules = $rules;

        $this->each(
            function (PriceRule $priceRule) {
                if ($this->hasMoreThan($priceRule, 1)) {
                    throw NotUniquePriceRulesException::create();
                }
            }
        );
    }

    public function getPrice(Item $item, int $quantity): int
    {
        if ($quantity < 0) {
            throw QuantityLowerThanOneException::create();
        }

        $priceRule = $this->find($this->forItem($item))->first();
        if (!$priceRule) {
            throw NoPriceRuleForItemException::create($item);
        }

        return $priceRule->getPrice($quantity);
    }

    public function isEmpty(): bool
    {
        return count($this->rules) === 0;
    }

    public function each(callable $callback): void
    {
        array_map($callback, $this->rules);
    }

    public function count(): int
    {
        return count($this->rules);
    }

    private function hasMoreThan(PriceRule $priceRule, $expectedQuantity): bool
    {
        return $this->find($this->forPriceRule($priceRule))->count() > $expectedQuantity;
    }

    private function first(): ?PriceRule
    {
        $priceRule = current($this->rules);
        return $priceRule ?: null;
    }

    private function forItem(Item $item):\Closure
    {
        return function (PriceRule $ruleItem) use ($item) {
            return $ruleItem->isFor($item);
        };
    }

    private function forPriceRule(PriceRule $basePriceRule):\Closure
    {
        return function (PriceRule $priceRule) use ($basePriceRule) {
            return $basePriceRule->equals($priceRule);
        };
    }

    private function find(\Closure $callback): PriceRules
    {
        $priceRules = clone $this;
        $priceRules->rules = array_values(array_filter($this->rules, $callback));
        return $priceRules;
    }
}
