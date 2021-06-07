<?php

namespace App\Entity;

use App\Repository\RemediationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RemediationRepository::class)
 */
class Remediation
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=PointControle::class, inversedBy="remediations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pointControle;

    /**
     * @ORM\ManyToMany(targetEntity=AuditControle::class, mappedBy="remediations")
     */
    private $auditControles;

    public function __construct()
    {
        $this->auditControles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPointControle(): ?PointControle
    {
        return $this->pointControle;
    }

    public function setPointControle(?PointControle $pointControle): self
    {
        $this->pointControle = $pointControle;

        return $this;
    }

    /**
     * @return Collection|AuditControle[]
     */
    public function getAuditControles(): Collection
    {
        return $this->auditControles;
    }

    public function addAuditControle(AuditControle $auditControle): self
    {
        if (!$this->auditControles->contains($auditControle)) {
            $this->auditControles[] = $auditControle;
            $auditControle->addRemediation($this);
        }

        return $this;
    }

    public function removeAuditControle(AuditControle $auditControle): self
    {
        if ($this->auditControles->removeElement($auditControle)) {
            $auditControle->removeRemediation($this);
        }

        return $this;
    }
}
