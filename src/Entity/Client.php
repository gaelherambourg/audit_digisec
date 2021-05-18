<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $nom_contact;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $tel_contact;

    /**
     * @ORM\Column(type="string", length=50)
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
