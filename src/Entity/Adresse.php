<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdresseRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=50, maxMessage="Ce champ a un maximum de 50 caractères")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="Ce champ a un maximum de 255 caractères")
     */
    private $rue;

    /**
     * Regex pattern provenant de rgxdb.com/r/354H8M0X
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Regex(pattern="/^(?:[0-8]\d|9[0-8])\d{3}$/", message="Merci de renseigner un code postal valide.")
     * @Assert\Length(max=10, maxMessage="Ce champ a un maximum de 10 caractères") 
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="adresse")
     */
    private $societe;

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle($libelle): self
    {
        $this->libelle = $libelle;

        return $this;
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

    public function setVille($ville): self
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
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
