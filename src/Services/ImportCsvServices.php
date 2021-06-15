<?php

namespace App\Services;

use App\Entity\Chapitre;
use App\Entity\PointControle;
use App\Entity\Preuve;
use App\Entity\Recommandation;
use App\Entity\Referentiel;
use App\Entity\TypePreuve;
use App\Repository\AuditRepository;
use App\Repository\ChapitreRepository;
use App\Repository\RecommandationRepository;
use App\Repository\ReferentielRepository;
use App\Repository\TypePreuveRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;

class ImportCsvServices
{
    private const REFERENTIEL = 'referentiel.csv';
    private const CHAPITRE = 'chapitre.csv';
    private const RECOMMANDATION = 'recommandation.csv';
    private const TYPE_PREUVE = 'type_preuve.csv';
    private const POINT_CONTROLE = 'point_controle.csv';

    private $uploadCsvDir;
    private $referentielRepository;
    private $chapitreRepository;
    private $recommandationRepository;
    private $typePreuveRepository;
    private $entityManager;

    public function __construct(
        $uploadCsvDir,
        EntityManagerInterface $entityManager,
        ReferentielRepository $referentielRepository,
        ChapitreRepository $chapitreRepository,
        RecommandationRepository $recommandationRepository,
        TypePreuveRepository $typePreuveRepository
    ) {
        $this->uploadCsvDir = $uploadCsvDir;
        $this->entityManager = $entityManager;
        $this->referentielRepository = $referentielRepository;
        $this->chapitreRepository = $chapitreRepository;
        $this->recommandationRepository = $recommandationRepository;
        $this->typePreuveRepository = $typePreuveRepository;
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
        $uploadedFilePreuve = $csvRegisterForm->get('typePreuveCsv')->getData();
        $uploadedFileControle = $csvRegisterForm->get('pointControleCsv')->getData();

        // On donne un nom générique
        $newFileNameReferentiel = self::REFERENTIEL;
        $newFileNameChapitre = self::CHAPITRE;
        $newFileNameRecommandation = self::RECOMMANDATION;
        $newFileNamePreuve = self::TYPE_PREUVE;
        $newFileNameControle = self::POINT_CONTROLE;

        // on déplace le fichier dans le répertoire public avant sa destruction
        try {
            $uploadedFileReferentiel->move($this->getUploadCsvDir(), $newFileNameReferentiel);
            $uploadedFileChapitre->move($this->getUploadCsvDir(), $newFileNameChapitre);
            $uploadedFileRecommandation->move($this->getUploadCsvDir(), $newFileNameRecommandation);
            $uploadedFilePreuve->move($this->getUploadCsvDir(), $newFileNamePreuve);
            $uploadedFileControle->move($this->getUploadCsvDir(), $newFileNameControle);
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
        $typePreuveSuccess = true;

        // On ajoute le référentiel
        $fileStr = $this->getUploadCsvDir() . self::REFERENTIEL;
        $handle = fopen($fileStr, 'r');
        $i = 0;
        $errorInsert = "";
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $i++;
            $referentiel = new Referentiel();
            $referentiel
                ->setLibelle((string) $data[1])
                ->setTypeReferentiel((string) $data[2]);
            try {
                $this->entityManager->persist($referentiel);
                $this->entityManager->flush();
                $id = $referentiel->getId();
            } catch (\Exception $e) {
                $errorInsert = "L'import du référentiel a échoué lors de la ligne n° " . $i;
                $referentielSuccess = false;
            }
        }

        // On ajoute les chapitres
        if ($referentielSuccess != false) {
            $fileStr = $this->getUploadCsvDir() . self::CHAPITRE;
            $handle = fopen($fileStr, 'r');
            $i = 0;
            $referentielId = $this->referentielRepository->find($id);
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $i++;
                $chapitre = new Chapitre();
                $chapitre
                    ->setReferentiel($referentielId)
                    ->setLibelle((string) $data[2]);
                try {
                    $this->entityManager->persist($chapitre);
                } catch (\Exception $e) {
                    $errorInsert = "L'import des chapitres a échoué lors de la ligne n° " . $i;
                    $chapitreSuccess = false;
                }
            }
            $this->entityManager->flush();
            
            // On ajoute les recommandations
            if ($chapitreSuccess != false) {
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
                        $errorInsert = "L'import des recommandations a échoué lors de la ligne n° " . $i;
                        $recommandationSuccess = false;
                        break;
                    }
                    $oldValue = (int)$data[1];
                }
                $this->entityManager->flush();
                
                // On ajoute les types preuves
                if ($recommandationSuccess != false) {
                    $fileStr = $this->getUploadCsvDir() . self::TYPE_PREUVE;
                    $handle = fopen($fileStr, 'r');
                    $i = 0;
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        $i++;
                        $typePreuve = new TypePreuve();
                        $typePreuve->setTypePreuve1($data[1]);
                        $typePreuve->setTypePreuve2($data[2]);
                        $typePreuve->setTypePreuve3($data[3]);
                        $typePreuve->setTypePreuve4($data[4]);
                        try {
                            $this->entityManager->persist($typePreuve);
                        } catch (\Exception $e) {
                            $errorInsert = "L'import du type de preuve a échoué lors de la ligne n° " . $i;
                            $typePreuveSuccess = false;
                        }
                    }
                    $this->entityManager->flush();
                    $idPreuve = $typePreuve->getId();
                    
                    // On ajoute les points de contrôles
                    if ($typePreuveSuccess != false) {
                        $fileStr = $this->getUploadCsvDir() . self::POINT_CONTROLE;
                        $handle = fopen($fileStr, 'r');
                        $i = 0;
                        $y = 0;
                        $oldValue = 1;
                        $recommandationsId = $this->recommandationRepository->findByExampleField($id);
                        $typePreuveId = $this->typePreuveRepository->find($idPreuve);
                        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            $i++;
                            $pointControle = new PointControle();
                            if ($data[1] == $oldValue) {
                                $pointControle->setRecommandation($recommandationsId[$y]);
                            } else {
                                $y++;
                                $pointControle->setRecommandation($recommandationsId[$y]);
                            }
                            $pointControle->setTypePreuve($typePreuveId);
                            $pointControle->setLibelle((string) $data[3]);
                            $pointControle->setTypeCritere((string) $data[4]);
                            try {
                                $this->entityManager->persist($pointControle);
                            } catch (\Exception $e) {
                                $errorInsert = "L'import des points de contrôles a échoué lors de la ligne n° " . $i;
                            }
                            $oldValue = $data[1];
                        }
                        $this->entityManager->flush();
                    }
                }
            }
        }
        return ['errorInsert' => $errorInsert];
    }

    /**
     * Procédure de suppression du fichier CSV dans le répertoire "/public/files/"
     */
    public function deleteCsvFile()
    {
        unlink($this->getUploadCsvDir() . self::REFERENTIEL);
        unlink($this->getUploadCsvDir() . self::CHAPITRE);
        unlink($this->getUploadCsvDir() . self::RECOMMANDATION);
        unlink($this->getUploadCsvDir() . self::TYPE_PREUVE);
        unlink($this->getUploadCsvDir() . self::POINT_CONTROLE);
    }
}
