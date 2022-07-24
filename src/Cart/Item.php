<?php

declare(strict_types=1);

namespace Library\Cart;

use Library\Product\Product;

final class Item
{
    public function __construct(
        private Product $product,
    ) {

    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Returns price in pence
     * @return int
     */
    public function getPrice(): int
    {
        return intval($this->product->getPrice() * 100);
    }
}