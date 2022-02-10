<?php

declare(strict_types=1);

namespace App\Domain\Phone\ValueObject;

use App\Domain\Phone\Exception\InvalidPriceException;

final class Price
{
    private float $price;

    private const DEFAULT_CURRENCY = '€';

    public function __construct(float $price)
    {
        if (0 > $price) {
            throw new InvalidPriceException();
        }
        $this->price = 100 * round($price, 2);
    }

    public function getPriceInCents(): int
    {
        return (int) $this->price;
    }

    public function getPrice(): float
    {
        return $this->price / 100;
    }

    public function __toString(): string
    {
        return sprintf('%.2f %s', round($this->getPrice(), 2), self::DEFAULT_CURRENCY);
    }
}
