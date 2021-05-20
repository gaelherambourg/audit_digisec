<?php

namespace App\Entity;

use App\Repository\TypePreuveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypePreuveRepository::class)
 */
class TypePreuve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $type_preuve_1;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $type_preuve_2;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $type_preuve_3;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $type_preuve_4;

    /**
     * @ORM\OneToMany(targetEntity=PointControle::class, mappedBy="typePreuve")
     */
    private $points_controle;

    public function __construct()
    {
        $this->points_controle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypePreuve1(): ?string
    {
        return $this->type_preuve_1;
    }

    public function setTypePreuve1(?string $type_preuve_1): self
    {
        $this->type_preuve_1 = $type_preuve_1;

        return $this;
    }

    public function getTypePreuve2(): ?string
    {
        return $this->type_preuve_2;
    }

    public function setTypePreuve2(?string $type_preuve_2): self
    {
        $this->type_preuve_2 = $type_preuve_2;

        return $this;
    }

    public function getTypePreuve3(): ?string
    {
        return $this->type_preuve_3;
    }

    public function setTypePreuve3(?string $type_preuve_3): self
    {
        $this->type_preuve_3 = $type_preuve_3;

        return $this;
    }

    public function getTypePreuve4(): ?string
    {
        return $this->type_preuve_4;
    }

    public function setTypePreuve4(?string $type_preuve_4): self
    {
        $this->type_preuve_4 = $type_preuve_4;

        return $this;
    }

    /**
     * @return Collection|PointControle[]
     */
    public function getPointsControle(): Collection
    {
        return $this->points_controle;
    }

    public function addPointsControle(PointControle $pointsControle): self
    {
        if (!$this->points_controle->contains($pointsControle)) {
            $this->points_controle[] = $pointsControle;
            $pointsControle->setTypePreuve($this);
        }

        return $this;
    }

    public function removePointsControle(PointControle $pointsControle): self
    {
        if ($this->points_controle->removeElement($pointsControle)) {
            // set the owning side to null (unless already changed)
            if ($pointsControle->getTypePreuve() === $this) {
                $pointsControle->setTypePreuve(null);
            }
        }

        return $this;
    }
}
