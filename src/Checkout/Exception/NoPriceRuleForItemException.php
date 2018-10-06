<?php

namespace Kata\Checkout\Exception;


use Kata\Checkout\Item;

class NoPriceRuleForItemException extends Exception
{
    public static function create(Item $item): NoPriceRuleForItemException
    {
        return new self(sprintf('There is not price rules for item %s!', (string) $item));
    }
}
