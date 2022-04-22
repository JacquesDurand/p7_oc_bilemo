<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['security' => "is_granted('IS_AUTHENTICATED_FULLY')"],
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'security_message' => "You don't have the correct rights to execute this operation",
            'openapi_context' => [
                'summary' => 'hidden',
            ],
        ],
    ],
    itemOperations: [
        'get' => ['security' => "is_granted('IS_AUTHENTICATED_FULLY')"],
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
    ],
    cacheHeaders: [
        'max_age' => 7200,
        'shared_max_age' => 7200,
        'vary' => ['Authorization', 'Accept-Language'],
    ]
)]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $builder;

    #[ORM\Column(type: 'string', length: 255)]
    private string $model;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 70)]
    private string $color;

    #[ORM\Column(type: 'integer')]
    private int $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuilder(): ?string
    {
        return $this->builder;
    }

    public function setBuilder(string $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
