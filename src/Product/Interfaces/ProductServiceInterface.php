<?php

declare(strict_types=1);

namespace Library\Product\Interfaces;

use Library\Product\Product;

interface ProductServiceInterface
{
    public function getProducts(): array;

    public function getProductByCode(string $code): Product;
}