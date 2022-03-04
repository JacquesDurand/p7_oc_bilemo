<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $phoneNumber;

    #[ORM\ManyToMany(targetEntity: Reseller::class, mappedBy: 'customers')]
    private $resellers;

    public function __construct()
    {
        $this->resellers = new ArrayCollection();
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

    /**
     * @return Collection<int, Reseller>
     */
    public function getResellers(): Collection
    {
        return $this->resellers;
    }

    public function addReseller(Reseller $reseller): self
    {
        if (!$this->resellers->contains($reseller)) {
            $this->resellers[] = $reseller;
            $reseller->addCustomer($this);
        }

        return $this;
    }

    public function removeReseller(Reseller $reseller): self
    {
        if ($this->resellers->removeElement($reseller)) {
            $reseller->removeCustomer($this);
        }

        return $this;
    }
}
