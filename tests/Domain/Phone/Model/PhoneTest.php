<?php

declare(strict_types=1);

namespace App\Tests\Domain\Phone\Model;

use App\Domain\Phone\Model\Phone;
use App\Domain\Phone\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    public function testChangeDescription()
    {
        $phone = new Phone('Fairphone', 'black', 'wow a fairphone', '3+', new Price(300.35));
        $phone->changeDescription('a better description');

        self::assertSame('a better description', $phone->getDescription());
    }
}
