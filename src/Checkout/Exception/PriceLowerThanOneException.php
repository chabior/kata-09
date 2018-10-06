<?php

namespace Kata\Checkout\Exception;


class PriceLowerThanOneException extends Exception
{
    public static function create(): PriceLowerThanOneException
    {
        throw new static('Price cant be lower than 1');
    }
}