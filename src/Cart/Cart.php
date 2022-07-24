<?php

declare(strict_types=1);

namespace Library\Cart;

use Library\Cart\Exceptions\CartAlreadyExistsException;
use Library\Cart\Exceptions\CartItemAlreadyExists;
use Library\Product\Interfaces\ProductServiceInterface;
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
        private readonly ProductServiceInterface $productService
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

    public function addDiscount(int $discount, string $type = Discount::PERCENTAGE): self
    {
        $discount = new Discount(amount: $discount, type: $type);
        $this->discounts[] = $discount;

        return $this;
    }

    public function addItem(string $productCode): self
    {
        $product = $this->productService->getProductByCode($productCode);

        if (array_key_exists($product->getCode(), $this->getItems())) {
            throw new CartItemAlreadyExists('Cart item already exists.');
        }

        $item = new Item(product: $product);
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

        if (! empty($this->discounts)) {
            foreach ($this->discounts as $discount) {
                $total -= $discount->calculate(total: $total);
            }
        }

        return $total;
    }
}