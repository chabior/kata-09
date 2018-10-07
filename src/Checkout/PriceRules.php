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

    public function addPriceRule(PriceRuleInterface $priceRule): PriceRules
    {
        if ($this->find($this->forPriceRule($priceRule))->count() > 0) {
            throw NotUniquePriceRulesException::create();
        }

        $rules = $this->rules;
        $rules[] = $priceRule;

        $priceRules = clone $this;
        $priceRules->rules = $rules;
        return $priceRules;
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

    private function first(): ?PriceRuleInterface
    {
        $priceRule = current($this->rules);
        return $priceRule ?: null;
    }

    private function forItem(Item $item):\Closure
    {
        return function (PriceRuleInterface $ruleItem) use ($item) {
            return $ruleItem->isFor($item);
        };
    }

    private function forPriceRule(PriceRuleInterface $basePriceRule):callable
    {
        return function (PriceRule $priceRule) use ($basePriceRule) {
            return $basePriceRule->equals($priceRule);
        };
    }

    private function find(callable $callback): PriceRules
    {
        $priceRules = clone $this;
        $priceRules->rules = array_values(array_filter($this->rules, $callback));
        return $priceRules;
    }
}
