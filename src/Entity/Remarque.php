<?php

namespace App\Entity;

use App\Repository\RemarqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RemarqueRepository::class)
 */
class Remarque
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarque;

    /**
     * @ORM\ManyToOne(targetEntity=Audit::class, inversedBy="remarques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $audit;

    /**
     * @ORM\ManyToOne(targetEntity=Recommandation::class, inversedBy="remarques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recommandation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getAudit(): ?Audit
    {
        return $this->audit;
    }

    public function setAudit(?Audit $audit): self
    {
        $this->audit = $audit;

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
}
