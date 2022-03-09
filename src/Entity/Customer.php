<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['security' => "is_granted('ROLE_ADMIN') or object.reseller == user"],
        'post' => ['security' => "is_granted('ROLE_ADMIN') or object.reseller == user"],
    ],
    itemOperations: [
        'get' => ['security' => "is_granted('ROLE_ADMIN') or object.reseller == user"],
        'put' => ['security' => "is_granted('ROLE_ADMIN') or object.reseller == user"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN') or object.reseller == user"],
    ],
    denormalizationContext: [
        'groups' => ['customer:write'],
    ],
    normalizationContext: [
        'groups' => ['customer:read'],
    ]
)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('customer:read')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('customer:write')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    private $phoneNumber;

    #[ORM\ManyToOne(targetEntity: Reseller::class, inversedBy: 'cutomers')]
    private $reseller;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getReseller(): Reseller
    {
        return $this->reseller;
    }

    public function setReseller(Reseller $reseller): self
    {
        $this->reseller = $reseller;
        $reseller->addCustomer($this);

        return $this;
    }
}
