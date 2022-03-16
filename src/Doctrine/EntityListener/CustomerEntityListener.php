<?php

declare(strict_types=1);

namespace App\Doctrine\EntityListener;

use App\Entity\Customer;
use App\Entity\Reseller;
use Symfony\Component\Security\Core\Security;

class CustomerEntityListener
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Customer $customer): void
    {
        if (null !== $customer->getReseller()) {
            return;
        }
        /** @var Reseller $reseller */
        if ($reseller = $this->security->getUser()) {
            $customer->setReseller($reseller);
        }
    }
}
