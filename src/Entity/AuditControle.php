<?php

namespace App\Entity;

use App\Repository\AuditControleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=AuditControleRepository::class)
 */
class AuditControle
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
    private $indexReferentiel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarque;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("integer", message="La valeur doit être un nombre")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=PointControle::class, inversedBy="audits_controle")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pointControle;

    /**
     * @ORM\OneToMany(targetEntity=Preuve::class, mappedBy="auditControle")
     */
    private $preuves;

    /**
     * @ORM\ManyToOne(targetEntity=Audit::class, inversedBy="audits_controle")
     * @ORM\JoinColumn(nullable=false)
     */
    private $audit;

    /**
     * @ORM\ManyToMany(targetEntity=Remediation::class, inversedBy="auditControles")
     */
    private $remediations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $est_valide;

    /**
     * @ORM\ManyToOne(targetEntity=Recommandation::class, inversedBy="audit_controles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recommandation;

    public function __construct()
    {
        $this->preuves = new ArrayCollection();
        $this->remediations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndexReferentiel(): ?string
    {
        return $this->indexReferentiel;
    }

    public function setIndexReferentiel(?string $indexReferentiel): self
    {
        $this->indexReferentiel = $indexReferentiel;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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

    public function getPointControle(): ?PointControle
    {
        return $this->pointControle;
    }

    public function setPointControle(?PointControle $pointControle): self
    {
        $this->pointControle = $pointControle;

        return $this;
    }

    /**
     * @return Collection|Preuve[]
     */
    public function getPreuves(): Collection
    {
        return $this->preuves;
    }

    public function addPreufe(Preuve $preufe): self
    {
        if (!$this->preuves->contains($preufe)) {
            $this->preuves[] = $preufe;
            $preufe->setAuditControle($this);
        }

        return $this;
    }

    public function removePreufe(Preuve $preufe): self
    {
        if ($this->preuves->removeElement($preufe)) {
            // set the owning side to null (unless already changed)
            if ($preufe->getAuditControle() === $this) {
                $preufe->setAuditControle(null);
            }
        }

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

    /**
     * @Assert\Callback
     **/
    public function isNoteValid(ExecutionContextInterface $context, $note)
    {
        $note = $this->getNote();
        $echelleNotation = $this->getAudit()->getEchelleNotation()->getEchelle();
        if(substr($echelleNotation, -1) == "5"){
            if($note < 0 || $note > 5 || \is_string($note)){
                $context->buildViolation('La note doit être comprise entre 0 et 5')
                    ->atPath('note')
                    ->addViolation();
            }
        }
        if(substr($echelleNotation, -1) == "3"){
            if($note < 0 || $note > 3 || \is_string($note)){
                $context->buildViolation('La note doit être comprise entre 0 et 3')
                    ->atPath('note')
                    ->addViolation();
            }
        }
    }

    /**
     * @return Collection|Remediation[]
     */
    public function getRemediations(): Collection
    {
        return $this->remediations;
    }

    public function addRemediation(Remediation $remediation): self
    {
        if (!$this->remediations->contains($remediation)) {
            $this->remediations[] = $remediation;
        }

        return $this;
    }

    public function removeRemediation(Remediation $remediation): self
    {
        $this->remediations->removeElement($remediation);

        return $this;
    }

    public function getEstValide(): ?bool
    {
        return $this->est_valide;
    }

    public function setEstValide(bool $est_valide): self
    {
        $this->est_valide = $est_valide;

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
