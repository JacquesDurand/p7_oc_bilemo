<?php

declare(strict_types=1);

namespace App\DataFixtures\DataProvider;

use App\Entity\Reseller;
use Faker\Generator;
use Faker\Provider\Base;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherProvider extends Base
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(Generator $generator, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($generator);
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword((new Reseller()), $plainPassword);
    }
}
