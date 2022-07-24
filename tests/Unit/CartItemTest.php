<?php

declare(strict_types=1);

namespace Tests\Unit;

use Library\Cart\Cart;
use Library\Cart\Item;
use Library\Product\Product;
use PHPUnit\Framework\TestCase;

final class CartItemTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider priceDataProvider
     */
    public function test_price_is_converted_to_pence_correctly($price, $discountedPrice): void
    {
        $product = new Product('P001', 'Photography', $price);
        $item = new Item($product);

        $this->assertSame($discountedPrice, $item->getPrice());
    }

    public function priceDataProvider(): array
    {
        return [
            [
                200.00,
                20000,
            ],
            [
                85.50,
                8550,
            ],
            [
                125.89,
                12589,
            ],
        ];
    }
}