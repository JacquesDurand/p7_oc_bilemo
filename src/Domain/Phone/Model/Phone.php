<?php

declare(strict_types=1);

namespace App\Domain\Phone\Model;

use App\Domain\Phone\ValueObject\Price;
use Ramsey\Uuid\Uuid;

class Phone
{
    private string $id;

    private string $builder;

    private string $model;

    private string $color;

    private string $description;

    private Price $price;

    public function __construct(
        string $builder,
        string $color,
        string $description,
        string $model,
        Price $price
    ) {
        $this->id = (string) Uuid::uuid4();
        $this->builder = $builder;
        $this->color = $color;
        $this->description = $description;
        $this->model = $model;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBuilder(): string
    {
        return $this->builder;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price->getPrice();
    }

    public function getFormattedPrice(): string
    {
        return (string) $this->price;
    }
}
