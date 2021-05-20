<?php

namespace App\Entity;

use App\Repository\EchelleNotationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EchelleNotationRepository::class)
 */
class EchelleNotation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $echelle;

    /**
     * @ORM\OneToMany(targetEntity=Audit::class, mappedBy="echelle_notation")
     */
    private $audits;

    public function __construct()
    {
        $this->audits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEchelle(): ?string
    {
        return $this->echelle;
    }

    public function setEchelle(string $echelle): self
    {
        $this->echelle = $echelle;

        return $this;
    }

    /**
     * @return Collection|Audit[]
     */
    public function getAudits(): Collection
    {
        return $this->audits;
    }

    public function addAudit(Audit $audit): self
    {
        if (!$this->audits->contains($audit)) {
            $this->audits[] = $audit;
            $audit->setEchelleNotation($this);
        }

        return $this;
    }

    public function removeAudit(Audit $audit): self
    {
        if ($this->audits->removeElement($audit)) {
            // set the owning side to null (unless already changed)
            if ($audit->getEchelleNotation() === $this) {
                $audit->setEchelleNotation(null);
            }
        }

        return $this;
    }
}
