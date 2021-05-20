<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SocieteRepository;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SocieteRepository::class)
 */
class Societe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $raison_social;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $immat_rcs;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $type_entreprise;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Type(type="integer", message="Merci de renseigner une valeur numérique.")
     */
    private $capital_social;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(mimeTypesMessage="Merci de choisir un fichier image valide.")
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_modification;

    /**
     * @ORM\OneToOne(targetEntity=Adresse::class, inversedBy="societe", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $adresse;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="societe", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity=Audit::class, mappedBy="societe")
     */
    private $audits;

    public function __construct()
    {
        $this->audits = new ArrayCollection();
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

    public function getRaisonSocial(): ?string
    {
        return $this->raison_social;
    }

    public function setRaisonSocial(string $raison_social): self
    {
        $this->raison_social = $raison_social;

        return $this;
    }

    public function getImmatRcs(): ?string
    {
        return $this->immat_rcs;
    }

    public function setImmatRcs(string $immat_rcs): self
    {
        $this->immat_rcs = $immat_rcs;

        return $this;
    }

    public function getTypeEntreprise(): ?string
    {
        return $this->type_entreprise;
    }

    public function setTypeEntreprise(string $type_entreprise): self
    {
        $this->type_entreprise = $type_entreprise;

        return $this;
    }

    public function getCapitalSocial(): ?int
    {
        return $this->capital_social;
    }

    public function setCapitalSocial($capital_social): self
    {
        $this->capital_social = $capital_social;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse( $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getContact(): ?Client
    {
        return $this->contact;
    }

    public function setContact(?Client $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
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
            $audit->setSociete($this);
        }

        return $this;
    }

    public function removeAudit(Audit $audit): self
    {
        if ($this->audits->removeElement($audit)) {
            // set the owning side to null (unless already changed)
            if ($audit->getSociete() === $this) {
                $audit->setSociete(null);
            }
        }

        return $this;
    }
}
