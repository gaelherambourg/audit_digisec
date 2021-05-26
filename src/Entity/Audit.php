<?php

namespace App\Entity;

use App\Repository\AuditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuditRepository::class)
 */
class Audit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $objectif_perimetre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $role_responsabilite;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $contraintes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_modification;

    /**
     * @ORM\OneToMany(targetEntity=AuditControle::class, mappedBy="audit")
     */
    private $audits_controle;

    /**
     * @ORM\ManyToOne(targetEntity=EchelleNotation::class, inversedBy="audits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $echelle_notation;

    /**
     * @ORM\OneToMany(targetEntity=Remarque::class, mappedBy="audit")
     */
    private $remarques;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="audits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $societe;

    /**
     * @ORM\ManyToOne(targetEntity=Statut::class, inversedBy="audits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="audits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    public function __construct()
    {
        $this->audits_controle = new ArrayCollection();
        $this->remarques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getObjectifPerimetre(): ?string
    {
        return $this->objectif_perimetre;
    }

    public function setObjectifPerimetre(string $objectif_perimetre): self
    {
        $this->objectif_perimetre = $objectif_perimetre;

        return $this;
    }

    public function getRoleResponsabilite(): ?string
    {
        return $this->role_responsabilite;
    }

    public function setRoleResponsabilite(string $role_responsabilite): self
    {
        $this->role_responsabilite = $role_responsabilite;

        return $this;
    }

    public function getContraintes(): ?string
    {
        return $this->contraintes;
    }

    public function setContraintes(string $contraintes): self
    {
        $this->contraintes = $contraintes;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(?\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

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
            $auditsControle->setAudit($this);
        }

        return $this;
    }

    public function removeAuditsControle(AuditControle $auditsControle): self
    {
        if ($this->audits_controle->removeElement($auditsControle)) {
            // set the owning side to null (unless already changed)
            if ($auditsControle->getAudit() === $this) {
                $auditsControle->setAudit(null);
            }
        }

        return $this;
    }

    public function getEchelleNotation(): ?EchelleNotation
    {
        return $this->echelle_notation;
    }

    public function setEchelleNotation(?EchelleNotation $echelle_notation): self
    {
        $this->echelle_notation = $echelle_notation;

        return $this;
    }

    
    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

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
            $remarque->setAudit($this);
        }

        return $this;
    }

    public function removeRemarque(Remarque $remarque): self
    {
        if ($this->remarques->removeElement($remarque)) {
            // set the owning side to null (unless already changed)
            if ($remarque->getAudit() === $this) {
                $remarque->setAudit(null);
            }
        }

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }
}
