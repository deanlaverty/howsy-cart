<?php

declare(strict_types=1);

namespace Library\Product;

final class Product
{
    public function __construct(
        private readonly string $code,
        private readonly string $name,
        private readonly int    $price
    ) {

    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}