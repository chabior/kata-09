<?php

namespace Kata\Checkout;


class Item
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function equals(Item $item): bool
    {
        return $this->name === $item->name;
    }

    public function toString():string
    {
        return (string)$this;
    }

    public function __toString()
    {
        return $this->name;
    }
}