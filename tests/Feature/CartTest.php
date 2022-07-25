<?php

declare(strict_types=1);

namespace Tests\Feature;

use DI\Container;
use Library\Cart\Cart;
use Library\Cart\Discount;
use Library\Cart\Exceptions\CartAlreadyExistsException;
use Library\Cart\Exceptions\CartItemAlreadyExists;
use Library\Product\Exceptions\ProductNotFoundException;
use Library\Product\Interfaces\ProductServiceInterface;
use Library\Product\Product;
use Library\User\Interfaces\UserServiceInterface;
use Library\User\User;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class CartTest extends TestCase
{
    protected Container $container;
    private Cart $cart;

    public function setUp(): void
    {
        $this->container = require __DIR__ . '../../bootstrap.php';
        $this->cart = $this->container->get(Cart::class);

        parent::setUp();
    }

    public function test_cart_can_be_created_successfully(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);

        $this->assertInstanceOf(Cart::class, $cart);
    }

    public function test_cart_throws_exception_when_created_twice(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);

        $this->expectException(CartAlreadyExistsException::class);
        $this->expectErrorMessage('Cart already exists for user.');

        $cart->create($user);
    }

    public function test_cart_can_be_created_without_discount(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);

        $this->assertEquals($cart->getDiscounts(), []);
    }

    public function test_cart_can_be_created_with_discount(): void
    {
        $this->mockCartWithDiscount();

        $user = new User(1, 'John Doe');
        $cart = $this->container->get(Cart::class);

        $cart = $cart->create($user);

        $discount = new Discount(
            Cart::USER_AGREED_CONTRACT_DISCOUNT,
            Discount::PERCENTAGE
        );

        $expectedDiscount = $cart->getDiscounts()[0];
        $this->assertEquals($discount, $expectedDiscount);
    }

    public function test_cart_can_add_item(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);
        $product = new Product('P001', 'Photography', 200.00);

        $cart->addItem($product->getCode());

        $this->assertArrayHasKey($product->getCode(), $cart->getItems());
        $this->assertSame($product->getPrice(), $cart->getTotal());
    }

    public function test_cart_can_not_add_same_item_twice(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);
        $product = new Product('P001', 'Photography', 200.00);

        $cart->addItem($product->getCode());

        $this->expectException(CartItemAlreadyExists::class);
        $this->expectErrorMessage('Cart item already exists.');

        $cart->addItem($product->getCode());
    }

    public function test_cart_can_not_add_invalid_item(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);
        $product = new Product('INVALIDCODE', 'Photography', 200);

        $this->expectException(ProductNotFoundException::class);
        $this->expectErrorMessage('Product not found.');

        $cart->addItem($product->getCode());
    }

    public function test_cart_can_calculate_correct_total(): void
    {
        $user = new User(1, 'John Doe');
        $cart = $this->cart->create($user);
        $product = new Product('P001', 'Photography', 200.00);
        $productTwo = new Product('P002', 'Floorplan', 100.00);

        $cart->addItem($product->getCode());
        $cart->addItem($productTwo->getCode());

        $expectedTotal = $product->getPrice() + $productTwo->getPrice();

        $this->assertSame($expectedTotal, $cart->getTotal());
    }

    public function test_cart_can_calculate_correct_total_with_discount(): void
    {
        $this->mockCartWithDiscount();

        $user = new User(1, 'John Doe');
        $cart = $this->container->get(Cart::class);

        $cart = $cart->create($user);

        $discount = new Discount(
            Cart::USER_AGREED_CONTRACT_DISCOUNT,
            Discount::PERCENTAGE
        );

        $product = new Product('P001', 'Photography', 200.00);
        $productTwo = new Product('P003', 'Floorplan', 83.50);

        $cart->addItem($product->getCode());
        $cart->addItem($productTwo->getCode());

        $expectedTotal = 0;

        foreach ($cart->getItems() as $item) {
            $expectedTotal += $item->getPrice();
        }

        $expectedTotal -= $discount->calculate($expectedTotal);
        $expectedTotal = round($expectedTotal / 100, 2);

        $this->assertSame($expectedTotal, $cart->getTotal());
    }

    private function mockCartWithDiscount()
    {
        $userService = Mockery::mock(UserServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasAgreedToContract')
                ->once()
                ->andReturn(true);
        })->makePartial();

        $this->container->set(UserServiceInterface::class, $userService);

        $this->container->set(Cart::class, function (ContainerInterface $c) {
            return new Cart(
                $c->get(UserServiceInterface::class),
                $c->get(ProductServiceInterface::class)
            );
        });
    }
}
