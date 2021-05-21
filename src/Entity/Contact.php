<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=30, maxMessage="Ce champ a un maximum de 30 caractères")
     */
    private $nom_contact;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=30, maxMessage="Ce champ a un maximum de 30 caractères")
     */
    private $prenom_contact;

    /**
     * Regex pattern provenant de regex101 (gabriel hautclocq)
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Regex(pattern="/^(\+[0-9]{2}[.\-\s]?|00[.\-\s]?[0-9]{2}|0)([0-9]{1,3}[.\-\s]?(?:[0-9]{2}[.\-\s]?){4})$/", message="Merci de renseigner un numéro de téléphone valide.")
     * @Assert\Length(max=20, maxMessage="Ce champ a un maximum de 20 caractères") 
     */
    private $tel_contact;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Email(message="Merci de renseigner un mail valide.")
     * @Assert\Length(max=50, maxMessage="Ce champ a un maximum de 50 caractères")
     */
    private $email_contact;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Ce champ a un maximum de 100 caractères")
     */
    private $poste_contact;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="contact")
     */
    private $societe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomContact(): ?string
    {
        return $this->nom_contact;
    }

    public function setNomContact($nom_contact): self
    {
        $this->nom_contact = $nom_contact;

        return $this;
    }

    public function getPrenomContact(): ?string
    {
        return $this->prenom_contact;
    }

    public function setPrenomContact($prenom_contact): self
    {
        $this->prenom_contact = $prenom_contact;

        return $this;
    }

    public function getTelContact(): ?string
    {
        return $this->tel_contact;
    }

    public function setTelContact($tel_contact): self
    {
        $this->tel_contact = $tel_contact;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->email_contact;
    }

    public function setEmailContact($email_contact): self
    {
        $this->email_contact = $email_contact;

        return $this;
    }

    public function getPosteContact(): ?string
    {
        return $this->poste_contact;
    }

    public function setPosteContact($poste_contact): self
    {
        $this->poste_contact = $poste_contact;

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
}
