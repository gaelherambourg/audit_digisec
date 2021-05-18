<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
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
     */
    private $nom_contact;

    /**
     * Regex pattern provenant de regex101 (gabriel hautclocq)
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Regex(pattern="/^(\+[0-9]{2}[.\-\s]?|00[.\-\s]?[0-9]{2}|0)([0-9]{1,3}[.\-\s]?(?:[0-9]{2}[.\-\s]?){4})$/", message="Merci de renseigner un numéro de téléphone valide.") 
     */
    private $tel_contact;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide.")
     * @Assert\Email(message="Merci de renseigner un mail valide.")
     */
    private $mail_contact;

    /**
     * @ORM\OneToOne(targetEntity=Societe::class, mappedBy="contact", cascade={"persist", "remove"})
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

    public function setNomContact(string $nom_contact): self
    {
        $this->nom_contact = $nom_contact;

        return $this;
    }

    public function getTelContact(): ?string
    {
        return $this->tel_contact;
    }

    public function setTelContact(string $tel_contact): self
    {
        $this->tel_contact = $tel_contact;

        return $this;
    }

    public function getMailContact(): ?string
    {
        return $this->mail_contact;
    }

    public function setMailContact(string $mail_contact): self
    {
        $this->mail_contact = $mail_contact;

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
            $this->societe->setContact(null);
        }

        // set the owning side of the relation if necessary
        if ($societe !== null && $societe->getContact() !== $this) {
            $societe->setContact($this);
        }

        $this->societe = $societe;

        return $this;
    }
}
