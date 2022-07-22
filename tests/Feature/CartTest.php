<?php

declare(strict_types=1);

namespace Tests\Feature;

use DI\Container;
use Library\Cart\Cart;
use Library\User\Interfaces\UserServiceInterface;
use Library\User\User;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    protected Container $container;

    public function setUp(): void
    {
        $this->container = require __DIR__ . '../../bootstrap.php';

        parent::setUp();
    }

    public function test_cart_can_be_created_successfully(): void
    {
        $user = new User(1, 'John Doe');
        $cart = new Cart($this->container->get(UserServiceInterface::class));

        $cart = $cart->create($user);

        $this->assertInstanceOf(Cart::class, $cart);
    }

    public function test_cart_can_be_created_with_discount(): void
    {
        $user = new User(1, 'John Doe');
        $cart = new Cart($this->container->get(UserServiceInterface::class));

        $cart = $cart->create($user);

        $expectedDiscounts = [
            Cart::USER_AGREED_CONTRACT_DISCOUNT,
        ];

        $this->assertEquals($cart->getDiscounts(), $expectedDiscounts);
    }

    public function test_cart_can_be_created_without_discount(): void
    {
        $this->mockUserServiceWithNoDiscount();

        $user = new User(1, 'John Doe');
        $cart = new Cart($this->container->get(UserServiceInterface::class));

        $cart = $cart->create($user);

        $this->assertEquals($cart->getDiscounts(), []);
    }

    private function mockUserServiceWithNoDiscount()
    {
        $userService = Mockery::mock(UserServiceInterface::class, function(MockInterface $mock) {
            $mock->shouldReceive('hasAgreedToContract')
                ->once()
                ->andReturn(false);
        })->makePartial();

        $this->container->set(UserServiceInterface::class, $userService);
    }
}