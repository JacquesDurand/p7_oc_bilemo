<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\EntityListeners(['App\Doctrine\EntityListener\CustomerEntityListener'])]
#[ApiResource(
    collectionOperations: [
        'get' => [],
        'post' => [],
    ],
    itemOperations: [
        'get' => [],
        'put' => [],
        'delete' => [],
    ],
    attributes: ['pagination_enabled' => false],
    denormalizationContext: [
        'groups' => ['customer:write'],
    ],
    normalizationContext: [
        'groups' => ['customer:read'],
    ]
)]
class Customer implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('customer:read')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $phoneNumber;

    #[ORM\ManyToOne(targetEntity: Reseller::class, inversedBy: 'customers')]
    private ?Reseller $reseller;

    #[Groups('customer:write')]
    #[SerializedName('password')]
    private ?string $plainPassword;

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

    public function getReseller(): ?Reseller
    {
        return $this->reseller;
    }

    public function setReseller(Reseller $reseller): self
    {
        $this->reseller = $reseller;
        $reseller->addCustomer($this);

        return $this;
    }

    public function getPlainPassword(): string|null
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
