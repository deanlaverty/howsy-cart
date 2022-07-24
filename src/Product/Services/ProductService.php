<?php

declare(strict_types=1);

namespace Library\Product\Services;

use Library\Product\Exceptions\ProductNotFoundException;
use Library\Product\Interfaces\ProductServiceInterface;
use Library\Product\Product;

final class ProductService implements ProductServiceInterface
{
    public function getProducts(): array
    {
        $data = [
            [
                'code' => 'P001',
                'name' => 'Photography',
                'price' => 200,
            ],
            [
                'code' => 'P002',
                'name' => 'Floorplan',
                'price' => 100,
            ],
            [
                'code' => 'P003',
                'name' => 'Gas Certificate',
                'price' => 8350,
            ],
            [
                'code' => 'P004',
                'name' => 'EICR Certificate',
                'price' => 5100,
            ],
        ];

        $items = [];

        foreach ($data as $item) {
            $items[] = Product::fromArray($item);
        }

        return $items;
    }

    public function getProductByCode(string $code): Product
    {
        $products = $this->getProducts();

        foreach ($products as $product) {
            if ($code === $product->getCode()) {
                return $product;
            }
        }

        throw new ProductNotFoundException('Product not found.');
    }
}