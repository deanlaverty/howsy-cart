<?php

declare(strict_types=1);

namespace Library\Cart;

final class Discount
{
    public const PERCENTAGE = 'percentage';

    public function __construct(
        private readonly int $amount,
        private readonly string $type
    ) {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function calculate(int $total): int
    {
        return match ($this->getType()) {
            self::PERCENTAGE => $this->calculatePercentage($total),
            default => throw new \UnhandledMatchError('Unexpected discount type'),
        };
    }

    private function calculatePercentage(int $total): int
    {
        $discountedTotal = ($this->getAmount() / 100) * $total;

        return intval($discountedTotal);
    }
}
