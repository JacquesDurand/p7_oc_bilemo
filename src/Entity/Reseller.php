<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResellerRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'security_message' => "You don't have the correct rights to execute this operation",
            'openapi_context' => [
                'summary' => 'hidden',
            ],
        ],
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'security_message' => "You don't have the correct rights to execute this operation",
            'openapi_context' => [
                'summary' => 'hidden',
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'security_message' => "You don't have the correct rights to execute this operation",
            'openapi_context' => [
                'summary' => 'hidden',
            ],
        ],
        'put' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'security_message' => "You don't have the correct rights to execute this operation",
            'openapi_context' => [
                'summary' => 'hidden',
            ],
        ],
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'security_message' => "You don't have the correct rights to execute this operation",
            'openapi_context' => [
                'summary' => 'hidden',
            ],
        ],
    ]
)]
class Reseller implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $storeName = null;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'reseller', targetEntity: Customer::class, cascade: ['remove'])]
    private Collection $customers;

    #[Groups('reseller:write')]
    #[Assert\NotCompromisedPassword]
    #[Assert\NotBlank]
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStoreName(): ?string
    {
        return $this->storeName;
    }

    public function setStoreName(string $storeName): self
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->storeName;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        $this->customers->removeElement($customer);

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    // TODO remove when https://github.com/lexik/LexikJWTAuthenticationBundle/issues/881 is fixed
    public function getUsername(): string|null
    {
        return $this->storeName;
    }
}
