<?php

namespace App\Entity;

use App\Entity\Societe;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdresseRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $rue;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     */
    private $ville;

    /**
     * @ORM\OneToOne(targetEntity=Societe::class, mappedBy="adresse", cascade={"persist", "remove"})
     */
    private $societe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue($rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal($code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille( $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        // unset the owning side of the relation if necessary
        if ($societe === null && $this->societe !== null) {
            $this->societe->setAdresse(null);
        }

        // set the owning side of the relation if necessary
        if ($societe !== null && $societe->getAdresse() !== $this) {
            $societe->setAdresse($this);
        }

        $this->societe = $societe;

        return $this;
    }
}
