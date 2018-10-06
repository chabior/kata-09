<?php

namespace Kata\Checkout\Exception;


class NotUniquePriceRulesException extends Exception
{
    public static function create(): NotUniquePriceRulesException
    {
        return new self('There can be only one price rule for item!');
    }
}
