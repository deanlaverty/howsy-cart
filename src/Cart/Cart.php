<?php

declare(strict_types=1);

namespace Library\Cart;

use Library\Cart\Exceptions\CartAlreadyExistsException;
use Library\User\Interfaces\UserServiceInterface;
use Library\User\User;

final class Cart
{
    const USER_AGREED_CONTRACT_DISCOUNT = 10;

    private ?User $user = null;
    private array $discounts = [];

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

    public function getDiscounts(): array
    {
        return $this->discounts;
    }
}