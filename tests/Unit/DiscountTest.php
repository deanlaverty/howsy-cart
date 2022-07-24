<?php

declare(strict_types=1);

namespace Tests\Unit;

use Library\Cart\Cart;
use Library\Cart\Discount;
use PHPUnit\Framework\TestCase;

final class DiscountTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_discount_percentage_is_correct(): void
    {
        $discount = new Discount(
            Cart::USER_AGREED_CONTRACT_DISCOUNT,
            Discount::PERCENTAGE
        );

        $this->assertSame(20, $discount->calculate(200));
    }

    public function test_discount_does_not_work_for_non_percentages(): void
    {
        $discount = new Discount(
            Cart::USER_AGREED_CONTRACT_DISCOUNT,
            'fixed'
        );

        $this->expectException(\UnhandledMatchError::class);
        $this->expectErrorMessage('Unexpected discount type');

        $discount->calculate(200);
    }
}
