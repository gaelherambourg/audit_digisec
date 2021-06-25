<?php

namespace App\Services;

use App\Entity\Preuve;
use App\Entity\Chapitre;
use App\Entity\TypePreuve;
use App\Entity\Referentiel;
use App\Entity\PointControle;
use Doctrine\DBAL\Types\Type;
use App\Entity\Recommandation;
use App\Entity\Remediation;
use Doctrine\ORM\EntityManager;
use App\Repository\AuditRepository;
use App\Repository\ChapitreRepository;
use App\Repository\PointControleRepository;
use App\Repository\TypePreuveRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use App\Repository\RecommandationRepository;

class ImportCsvServices
{
    private const REFERENTIEL = 'referentiel.csv';
    private const CHAPITRE = 'chapitre.csv';
    private const RECOMMANDATION = 'recommandation.csv';
    private const POINT_CONTROLE = 'point_controle.csv';
    private const REMEDIATION = 'remediation.csv';

    private $uploadCsvDir;
    private $referentielRepository;
    private $chapitreRepository;
    private $recommandationRepository;
    private $typePreuveRepository;
    private $pointControleRepository;
    private $entityManager;

    public function __construct(
        $uploadCsvDir,
        EntityManagerInterface $entityManager,
        ReferentielRepository $referentielRepository,
        ChapitreRepository $chapitreRepository,
        RecommandationRepository $recommandationRepository,
        TypePreuveRepository $typePreuveRepository,
        PointControleRepository $pointControleRepository
    ) {
        $this->uploadCsvDir = $uploadCsvDir;
        $this->entityManager = $entityManager;
        $this->referentielRepository = $referentielRepository;
        $this->chapitreRepository = $chapitreRepository;
        $this->recommandationRepository = $recommandationRepository;
        $this->typePreuveRepository = $typePreuveRepository;
        $this->pointControleRepository = $pointControleRepository;
    }


    /**
     * Méthode téléchargeant le fichier dans l'emplacement "/public/files/".
     * Méthode retournant True si le téléchargment s'est correctement déroulé ; sinon False.
     * @param $csvRegisterForm - L'élément formulaire récupéré apres soumission.
     * @return bool - True si le téléchargement a été réalisé avec succes ; sinon False.
     */
    public function uploadCsvFile($csvRegisterForm): bool
    {
        $isItUploaded = true;
        /** @var UploadedFile $uploadedFile */

        // on récupère les fichiers
        $uploadedFileReferentiel = $csvRegisterForm->get('referentielCsv')->getData();
        $uploadedFileChapitre = $csvRegisterForm->get('chapitreCsv')->getData();
        $uploadedFileRecommandation = $csvRegisterForm->get('recommandationCsv')->getData();
        $uploadedFileControle = $csvRegisterForm->get('pointControleCsv')->getData();
        $uploadedFileRemediation = $csvRegisterForm->get('remediationCsv')->getData();

        // On donne un nom générique
        $newFileNameReferentiel = self::REFERENTIEL;
        $newFileNameChapitre = self::CHAPITRE;
        $newFileNameRecommandation = self::RECOMMANDATION;
        $newFileNameControle = self::POINT_CONTROLE;
        $newFileNameRemediation = self::REMEDIATION;

        // on déplace le fichier dans le répertoire public avant sa destruction
        try {
            $uploadedFileReferentiel->move($this->getUploadCsvDir(), $newFileNameReferentiel);
            $uploadedFileChapitre->move($this->getUploadCsvDir(), $newFileNameChapitre);
            $uploadedFileRecommandation->move($this->getUploadCsvDir(), $newFileNameRecommandation);
            $uploadedFileControle->move($this->getUploadCsvDir(), $newFileNameControle);
            $uploadedFileRemediation->move($this->getUploadCsvDir(), $newFileNameRemediation);
        } catch (\Exception $e) {
            $isItUploaded = false;
        }
        return $isItUploaded;
    }

    /**
     * Ascesseur de propriété : "$uploadCsvDir" (le répertoire d'accès au fichier CSV)
     * @return mixed
     */
    private function getUploadCsvDir()
    {
        return $this->uploadCsvDir;
    }

    /**
     * Méthode gérant l'insertion des nouveaux participants en base de données.
     * Elle retourne les éléments de résultats :
     * Message d'erreur à l'insertion,
     * message d'erreur des participants non insérés,
     * le nombre de ligne traitées,
     * le nombre de lignes non traitées.
     * @return array - Tableau avec les résultats de l'insertion en base.
     */
    public function insertCsvFile()
    {
        // Définition des varialbes de success
        $referentielSuccess = true;
        $chapitreSuccess = true;
        $recommandationSuccess = true;
        $pointControleSuccess = true;

        // On suspend l'auto-commit
        $this->entityManager->getConnection()->beginTransaction();

        // On ajoute le référentiel
        $fileStr = $this->getUploadCsvDir() . self::REFERENTIEL;
        $handle = fopen($fileStr, 'r');
        $i = 0;
        $errorInsert = "";
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            try {
                $i++;
                $referentiel = new Referentiel();
                $referentiel
                    ->setLibelle((string) $data[1])
                    ->setTypeReferentiel((string) $data[2]);
                $this->entityManager->persist($referentiel);
                $this->entityManager->flush();
                $id = $referentiel->getId();
            } catch (\Exception $e) {
                $errorInsert = "L'import du référentiel a échoué lors de la ligne n° " . $i . " - Aucun import enregistré en base de données";
                $referentielSuccess = false;
            }
        }

        // On ajoute les chapitres
        if ($referentielSuccess) {
            $fileStr = $this->getUploadCsvDir() . self::CHAPITRE;
            $handle = fopen($fileStr, 'r');
            $i = 0;
            $referentielId = $this->referentielRepository->find($id);
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                try {
                    $i++;
                    $chapitre = new Chapitre();
                    $chapitre->setReferentiel($referentielId);
                    if (!empty($data[2])) {
                        $chapitre->setLibelle($data[2]);
                    }
                    $this->entityManager->persist($chapitre);
                } catch (\Exception $e) {
                    $errorInsert = "L'import des chapitres a échoué lors de la ligne n° " . $i . " - Aucun import enregistré en base de données";
                    $chapitreSuccess = false;
                }
            }
            try {
                $this->entityManager->flush();
            } catch (\Exception $e) {
                $errorInsert = "L'import des chapitres a échoué - Aucun import enregistré en base de données";
                $chapitreSuccess = false;
            }

            // On ajoute les recommandations
            if ($chapitreSuccess) {
                $fileStr = $this->getUploadCsvDir() . self::RECOMMANDATION;
                $handle = fopen($fileStr, 'r');
                $i = 0;
                $y = 0;
                $oldValue = 1;
                $chapitreId = $this->chapitreRepository->chapitreParReferentiel($id);
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    try {
                        $i++;
                        $recommandation = new Recommandation();
                        if ((int) $data[1] === $oldValue) {
                            $recommandation->setChapitre($chapitreId[$y]);
                        } else {
                            $y++;
                            $recommandation->setChapitre($chapitreId[$y]);
                        }
                        $recommandation->setIndexReferentiel($data[2]);
                        $recommandation->setLibelle((string) $data[3]);
                        $recommandation->setDescription((string) $data[4]);
                        $this->entityManager->persist($recommandation);
                    } catch (\Exception $e) {
                        $errorInsert = "L'import des recommandations a échoué lors de la ligne n° " . $i . " - Aucun import enregistré en base de données";
                        $recommandationSuccess = false;
                    }
                    $oldValue = (int)$data[1];
                }
                try {
                    $this->entityManager->flush();
                } catch (\Exception $e) {
                    $errorInsert = "L'import des recommandations a échoué - Aucun import enregistré en base de données";
                    $recommandationSuccess = false;
                }

                // On ajoute les points de contrôles
                if ($recommandationSuccess) {
                    $fileStr = $this->getUploadCsvDir() . self::POINT_CONTROLE;
                    $handle = fopen($fileStr, 'r');
                    $i = 0;
                    $y = 0;
                    $oldValue = 1;
                    $recommandationsId = $this->recommandationRepository->findByExampleField($id);
                    $typePreuveId = $this->typePreuveRepository->findAll();
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        try {
                            $i++;
                            $pointControle = new PointControle();
                            if ($data[1] == $oldValue) {
                                $pointControle->setRecommandation($recommandationsId[$y]);
                            } else {
                                $y++;
                                $pointControle->setRecommandation($recommandationsId[$y]);
                            }
                            $pointControle->setTypePreuve($typePreuveId[0]);
                            $pointControle->setLibelle((string) $data[3]);
                            $pointControle->setTypeCritere((string) $data[4]);
                            $this->entityManager->persist($pointControle);
                        } catch (\Exception $e) {
                            $errorInsert = "L'import des points de contrôles a échoué lors de la ligne n° " . $i . " - Aucun import enregistré en base de données";
                        }
                        $oldValue = $data[1];
                    }
                    try {
                        $this->entityManager->flush();
                    } catch (\Exception $e) {
                        $errorInsert = "L'import des points de contrôles a échoué - Aucun import enregistré en base de données";
                        $pointControleSuccess = false;
                    }

                    // On ajoute les remediations
                    if ($pointControleSuccess) {
                        $fileStr = $this->getUploadCsvDir() . self::REMEDIATION;
                        $handle = fopen($fileStr, 'r');
                        $i = 0;
                        $y = 0;
                        $oldValue = 1;
                        $pointControleId = $this->pointControleRepository->findByExampleField($id);
                        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            try {
                                $i++;
                                $remediation = new Remediation();
                                if ($data[0] == $oldValue) {
                                    $remediation->setPointControle($pointControleId[$y]);
                                } else {
                                    $y++;
                                    $remediation->setPointControle($pointControleId[$y]);
                                }
                                $remediation->setLibelle((string) $data[1]);
                                $this->entityManager->persist($remediation);
                            } catch (\Exception $e) {
                                $errorInsert = "L'import des remédiations a échoué lors de la ligne n° " . $i . " - Aucun import enregistré en base de données";
                            }
                            $oldValue = $data[0];
                        }
                        try {
                            $this->entityManager->flush();
                        } catch (\Exception $e) {
                            $errorInsert = "L'import des remédiations a échoué - Aucun import enregistré en base de données";
                        }
                    }
                }
            }
        }
        // On vérifie qu'il n'y a pas eu d'erreurs
        if ($referentielSuccess && $chapitreSuccess && $recommandationSuccess && $pointControleSuccess) {
            $this->entityManager->getConnection()->commit();
            return ['errorInsert' => $errorInsert];
        } else {
            $this->entityManager->getConnection()->rollBack();
            return ['errorInsert' => $errorInsert];
        }
    }

    /**
     * Procédure de suppression du fichier CSV dans le répertoire "/public/files/"
     */
    public function deleteCsvFile()
    {
        unlink($this->getUploadCsvDir() . self::REFERENTIEL);
        unlink($this->getUploadCsvDir() . self::CHAPITRE);
        unlink($this->getUploadCsvDir() . self::RECOMMANDATION);
        unlink($this->getUploadCsvDir() . self::POINT_CONTROLE);
        unlink($this->getUploadCsvDir() . self::REMEDIATION);
    }
}
