<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CsvForm
{

    /**
     * @Assert\NotBlank(message="Merci de sélectionner un fichier .csv")
     * @Assert\File(
     *      maxSize = "2048k",
     *      maxSizeMessage="Votre fichier .csv ne doit pas excéder 2 Mo",
     *      mimeTypes = {"text/csv"},
     *      mimeTypesMessage = "Merci d'uploader un fichier de type .csv"
     *              )
     */
    private $referentielCsv;

    /**
     * @Assert\NotBlank(message="Merci de sélectionner un fichier .csv")
     * @Assert\File(
     *      maxSize = "2048k",
     *      maxSizeMessage="Votre fichier .csv ne doit pas excéder 2 Mo",
     *      mimeTypes = {"text/csv"},
     *      mimeTypesMessage = "Merci d'uploader un fichier de type .csv"
     *              )
     */
    private $chapitreCsv;

    /**
     * @Assert\NotBlank(message="Merci de sélectionner un fichier .csv")
     * @Assert\File(
     *      maxSize = "2048k",
     *      maxSizeMessage="Votre fichier .csv ne doit pas excéder 2 Mo",
     *      mimeTypes = {"text/csv"},
     *      mimeTypesMessage = "Merci d'uploader un fichier de type .csv"
     *              )
     */
    private $recommandationCsv;

    /**
     * @Assert\NotBlank(message="Merci de sélectionner un fichier .csv")
     * @Assert\File(
     *      maxSize = "2048k",
     *      maxSizeMessage="Votre fichier .csv ne doit pas excéder 2 Mo",
     *      mimeTypes = {"text/csv"},
     *      mimeTypesMessage = "Merci d'uploader un fichier de type .csv"
     *              )
     */
    private $pointControleCsv;

    /**
     * @Assert\NotBlank(message="Merci de sélectionner un fichier .csv")
     * @Assert\File(
     *      maxSize = "2048k",
     *      maxSizeMessage="Votre fichier .csv ne doit pas excéder 2 Mo",
     *      mimeTypes = {"text/csv"},
     *      mimeTypesMessage = "Merci d'uploader un fichier de type .csv"
     *              )
     */
    private $remediationCsv;

    public function getReferentielCsv()
    {
        return $this->referentielCsv;
    }

    public function setReferentielCsv($referentielCsv)
    {
        $this->referentielCsv = $referentielCsv;

        return $this;
    }

    public function getChapitreCsv()
    {
        return $this->chapitreCsv;
    }

    public function setChapitreCsv($chapitreCsv)
    {
        $this->chapitreCsv = $chapitreCsv;

        return $this;
    }

    public function getRecommandationCsv()
    {
        return $this->recommandationCsv;
    }

    public function setRecommandationCsv($recommandationCsv)
    {
        $this->recommandationCsv = $recommandationCsv;

        return $this;
    }

    public function getPointControleCsv()
    {
        return $this->pointControleCsv;
    }

    public function setPointControleCsv($pointControleCsv)
    {
        $this->pointControleCsv = $pointControleCsv;

        return $this;
    }

    public function getRemediationCsv()
    {
        return $this->remediationCsv;
    }

    public function setRemediationCsv($remediationCsv)
    {
        $this->remediationCsv = $remediationCsv;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    /*public function validateExtensionFile(ExecutionContextInterface $context)
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

        if ($this->pointControleCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('pointControleCsv')
                ->addViolation();
        }

        if ($this->remediationCsv->getClientOriginalExtension() != "csv") {
            $context->buildViolation('Veuillez télécharger un fichier .csv')
                ->atPath('remediationCsv')
                ->addViolation();
        }
    }*/
}
