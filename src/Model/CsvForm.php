<?php


namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CsvForm
{

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Votre fichier .csv ne doit pas excéder 1Mo",
     *     )
     */
    private $referentielCsv;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Votre fichier .csv ne doit pas excéder 1Mo",
     *     )
     */
    private $chapitreCsv;

        /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Votre fichier .csv ne doit pas excéder 1Mo",
     *     )
     */
    private $recommandationCsv;

        /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Votre fichier .csv ne doit pas excéder 1Mo",
     *     )
     */
    private $typePreuveCsv;

        /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Votre fichier .csv ne doit pas excéder 1Mo",
     *     )
     */
    private $pointControleCsv;


    /**
     * @return UploadedFile|null
     */
    public function getReferentielCsv(): ?UploadedFile
    {
        return $this->referentielCsv;
    }

    /**
     * @param UploadedFile|null $referentielCsv
     */
    public function setReferentielCsv(?UploadedFile $referentielCsv): void
    {
        $this->referentielCsv = $referentielCsv;
    }

    /**
     * Get maxSize = "1024k",
     */ 
    public function getChapitreCsv()
    {
        return $this->chapitreCsv;
    }

    /**
     * Set maxSize = "1024k",
     *
     * @return  self
     */ 
    public function setChapitreCsv($chapitreCsv)
    {
        $this->chapitreCsv = $chapitreCsv;

        return $this;
    }

    /**
     * Get maxSize = "1024k",
     */ 
    public function getRecommandationCsv()
    {
        return $this->recommandationCsv;
    }

    /**
     * Set maxSize = "1024k",
     *
     * @return  self
     */ 
    public function setRecommandationCsv($recommandationCsv)
    {
        $this->recommandationCsv = $recommandationCsv;

        return $this;
    }

    /**
     * Get maxSize = "1024k",
     */ 
    public function getTypePreuveCsv()
    {
        return $this->typePreuveCsv;
    }

    /**
     * Set maxSize = "1024k",
     *
     * @return  self
     */ 
    public function setTypePreuveCsv($typePreuveCsv)
    {
        $this->typePreuveCsv = $typePreuveCsv;

        return $this;
    }

    /**
     * Get maxSize = "1024k",
     */ 
    public function getPointControleCsv()
    {
        return $this->pointControleCsv;
    }

    /**
     * Set maxSize = "1024k",
     *
     * @return  self
     */ 
    public function setPointControleCsv($pointControleCsv)
    {
        $this->pointControleCsv = $pointControleCsv;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validateExtensionFile(ExecutionContextInterface $context)
    {
        if ($this->referentielCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('referentielCsv')
                ->addViolation();
        }
        
        if ($this->chapitreCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('chapitreCsv')
                ->addViolation();
        }

        if ($this->recommandationCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('recommandationCsv')
                ->addViolation();
        }

        if ($this->typePreuveCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('typePreuveCsv')
                ->addViolation();
        }

        if ($this->pointControleCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('pointControleCsv')
                ->addViolation();
        }
    }
}