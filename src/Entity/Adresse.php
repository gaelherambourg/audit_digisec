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
     * Regex pattern provenant de rgxdb.com/r/354H8M0X
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Regex(pattern="/^(?:[0-8]\d|9[0-8])\d{3}$/", message="Merci de renseigner un code postal valide.") 
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

    public function getCodePostal(): ?string
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
