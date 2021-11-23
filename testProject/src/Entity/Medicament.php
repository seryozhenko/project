<?php

namespace App\Entity;

use App\Entity\EntityInterface;
use App\Repository\MedicamentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="medicaments")
 * @ORM\Entity(repositoryClass=App\Repository\MedicamentRepository::class)
 */
class Medicament implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $medicamentName;

    /**
     * @ORM\Column(type="integer")
     */
    private $substanceId;

    /**
     * @ORM\Column(type="integer")
     */
    private $manufacturerId;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedicamentName(): ?string
    {
        return $this->medicamentName;
    }

    public function setMedicamentName(string $medicamentName): self
    {
        $this->medicamentName = $medicamentName;

        return $this;
    }

    public function getSubstanceId(): ?int
    {
        return $this->substanceId;
    }

    public function setSubstanceId(int $substanceId): self
    {
        $this->substanceId = $substanceId;

        return $this;
    }

    public function getManufacturerId(): ?int
    {
        return $this->manufacturerId;
    }

    public function setManufacturerId(int $manufacturerId): self
    {
        $this->manufacturerId = $manufacturerId;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
}
