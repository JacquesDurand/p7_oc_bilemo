<?php

declare(strict_types=1);

namespace App\Tests\Domain\Phone\Model\ValueObject;

use App\Domain\Phone\Exception\InvalidPriceException;
use App\Domain\Phone\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testConstruct()
    {
        $this->expectException(InvalidPriceException::class);
        new Price(-1);
    }

    public function testToString()
    {
        $price = new Price(3000.536);
        self::assertSame('3000.54 €', (string) $price);
    }

    public function testPriceInCents()
    {
        $price = new Price(3000.536);
        self::assertSame(300054, $price->getPriceInCents());
    }
}
