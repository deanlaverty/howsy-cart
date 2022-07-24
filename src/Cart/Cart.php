<?php

declare(strict_types=1);

namespace Library\Cart;

use Library\Cart\Exceptions\CartAlreadyExistsException;
use Library\Cart\Exceptions\CartItemAlreadyExists;
use Library\Product\Product;
use Library\User\Interfaces\UserServiceInterface;
use Library\User\User;

final class Cart
{
    const USER_AGREED_CONTRACT_DISCOUNT = 10;

    private ?User $user = null;
    private array $discounts = [];
    private array $items = [];

    public function __construct(
        private readonly UserServiceInterface $userService,
    ){

    }

    public function create(User $user): self
    {
        if ($this->user === $user) {
            throw new CartAlreadyExistsException('Cart already exists for user.');
        }

        if ($this->userService->hasAgreedToContract($user)) {
            $this->addDiscount(self::USER_AGREED_CONTRACT_DISCOUNT);
        }

        $this->user = $user;

        return $this;
    }

    public function addDiscount(int $discount): self
    {
        $this->discounts[] = $discount;

        return $this;
    }

    public function addItem(Product $product): self
    {
        if (array_key_exists($product->getCode(), $this->getItems())) {
            throw new CartItemAlreadyExists('Cart item already exists.');
        }

        $item = new Item($product);
        $this->items[$product->getCode()] = $item;

        return $this;
    }

    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getPrice();
        }

        return $total;
    }
}