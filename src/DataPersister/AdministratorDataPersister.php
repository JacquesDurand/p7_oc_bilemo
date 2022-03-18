<?php

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdministratorDataPersister implements DataPersisterInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function supports($data): bool
    {
        return $data instanceof Administrator;
    }

    /**
     * @param Administrator $data
     */
    public function persist($data): void
    {
        if ($plainPassword = $data->getPlainPassword()) {
            $data->setPassword(
                $this->passwordHasher->hashPassword($data, $plainPassword)
            );
            $data->eraseCredentials();
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @param Administrator $data
     */
    public function remove($data): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
