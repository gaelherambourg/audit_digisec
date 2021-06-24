<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\RecommandationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecommandationRepository::class)
 */
class Recommandation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $index_referentiel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="Ce champ a un maximum de 255 caractères")
     */
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Chapitre::class, inversedBy="recommandations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapitre;

    /**
     * @ORM\OneToMany(targetEntity=PointControle::class, mappedBy="recommandation", orphanRemoval=true)
     */
    private $points_controle;

    /**
     * @ORM\OneToMany(targetEntity=Remarque::class, mappedBy="recommandation")
     */
    private $remarques;

    /**
     * @ORM\OneToMany(targetEntity=AuditControle::class, mappedBy="recommandation")
     */
    private $audit_controles;

    public function __construct()
    {
        $this->points_controle = new ArrayCollection();
        $this->remarques = new ArrayCollection();
        $this->audit_controles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndexReferentiel(): ?string
    {
        return $this->index_referentiel;
    }

    public function setIndexReferentiel(?string $index_referentiel): self
    {
        $this->index_referentiel = $index_referentiel;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle($libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getChapitre(): ?Chapitre
    {
        return $this->chapitre;
    }

    public function setChapitre(?Chapitre $chapitre): self
    {
        $this->chapitre = $chapitre;

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
            $pointsControle->setRecommandation($this);
        }

        return $this;
    }

    public function removePointsControle(PointControle $pointsControle): self
    {
        if ($this->points_controle->removeElement($pointsControle)) {
            // set the owning side to null (unless already changed)
            if ($pointsControle->getRecommandation() === $this) {
                $pointsControle->setRecommandation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Remarque[]
     */
    public function getRemarques(): Collection
    {
        return $this->remarques;
    }

    public function addRemarque(Remarque $remarque): self
    {
        if (!$this->remarques->contains($remarque)) {
            $this->remarques[] = $remarque;
            $remarque->setRecommandation($this);
        }

        return $this;
    }

    public function removeRemarque(Remarque $remarque): self
    {
        if ($this->remarques->removeElement($remarque)) {
            // set the owning side to null (unless already changed)
            if ($remarque->getRecommandation() === $this) {
                $remarque->setRecommandation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AuditControle[]
     */
    public function getAuditControles(): Collection
    {
        return $this->audit_controles;
    }

    public function addAuditControle(AuditControle $auditControle): self
    {
        if (!$this->audit_controles->contains($auditControle)) {
            $this->audit_controles[] = $auditControle;
            $auditControle->setRecommandation($this);
        }

        return $this;
    }

    public function removeAuditControle(AuditControle $auditControle): self
    {
        if ($this->audit_controles->removeElement($auditControle)) {
            // set the owning side to null (unless already changed)
            if ($auditControle->getRecommandation() === $this) {
                $auditControle->setRecommandation(null);
            }
        }

        return $this;
    }
}
