<?php

namespace App\Entity;

use App\Entity\EntityInterface;
use App\Repository\ActiveSubstanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="active_substances")
 * @ORM\Entity(repositoryClass=App\Repository\ActiveSubstanceRepository::class)
 */
class ActiveSubstance implements EntityInterface
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
    private $substanceName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubstanceName(): ?string
    {
        return $this->substanceName;
    }

    public function setSubstanceName(string $substanceName): self
    {
        $this->substanceName = $substanceName;

        return $this;
    }
}
