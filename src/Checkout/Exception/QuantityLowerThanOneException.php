<?php

namespace Kata\Checkout\Exception;


class QuantityLowerThanOneException extends Exception
{
    public static function create(): QuantityLowerThanOneException
    {
        return new self('Quantity cant be lower than 1');
    }
}