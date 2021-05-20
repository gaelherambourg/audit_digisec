<?php

namespace App\Entity;

use App\Repository\PointControleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PointControleRepository::class)
 */
class PointControle
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
     * @ORM\Column(type="string", length=30)
     */
    private $type_critere;

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
     * @ORM\ManyToOne(targetEntity=Recommandation::class, inversedBy="points_controle")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recommandation;

    /**
     * @ORM\OneToMany(targetEntity=AuditControle::class, mappedBy="pointControle")
     */
    private $audits_controle;

    /**
     * @ORM\OneToMany(targetEntity=Remediation::class, mappedBy="pointControle")
     */
    private $remediations;

    public function __construct()
    {
        $this->audits_controle = new ArrayCollection();
        $this->remediations = new ArrayCollection();
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

    public function getTypeCritere(): ?string
    {
        return $this->type_critere;
    }

    public function setTypeCritere(string $type_critere): self
    {
        $this->type_critere = $type_critere;

        return $this;
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

    public function getRecommandation(): ?Recommandation
    {
        return $this->recommandation;
    }

    public function setRecommandation(?Recommandation $recommandation): self
    {
        $this->recommandation = $recommandation;

        return $this;
    }

    /**
     * @return Collection|AuditControle[]
     */
    public function getAuditsControle(): Collection
    {
        return $this->audits_controle;
    }

    public function addAuditsControle(AuditControle $auditsControle): self
    {
        if (!$this->audits_controle->contains($auditsControle)) {
            $this->audits_controle[] = $auditsControle;
            $auditsControle->setPointControle($this);
        }

        return $this;
    }

    public function removeAuditsControle(AuditControle $auditsControle): self
    {
        if ($this->audits_controle->removeElement($auditsControle)) {
            // set the owning side to null (unless already changed)
            if ($auditsControle->getPointControle() === $this) {
                $auditsControle->setPointControle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Remediation[]
     */
    public function getRemediations(): Collection
    {
        return $this->remediations;
    }

    public function addRemediation(Remediation $remediation): self
    {
        if (!$this->remediations->contains($remediation)) {
            $this->remediations[] = $remediation;
            $remediation->setPointControle($this);
        }

        return $this;
    }

    public function removeRemediation(Remediation $remediation): self
    {
        if ($this->remediations->removeElement($remediation)) {
            // set the owning side to null (unless already changed)
            if ($remediation->getPointControle() === $this) {
                $remediation->setPointControle(null);
            }
        }

        return $this;
    }
}
