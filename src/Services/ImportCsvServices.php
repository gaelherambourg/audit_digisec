<?php

namespace App\Services;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;

class ImportCsvServices
{
    private const FILE_NAME = 'csv_test.csv';

    private $uploadCsvDir;
    private $participantRepository;
    private $campusRepository;
    private $passwordEncoder;
    private $entityManager;


    public function __construct($uploadCsvDir, EntityManagerInterface $entityManager)
    {
        $this->uploadCsvDir = $uploadCsvDir;
        $this->entityManager = $entityManager;
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
        $uploadedFile = $csvRegisterForm->get('csvFile')->getData();
        // on génere un nom de fichier générique
        $newFileName = self::FILE_NAME;
        // on déplace le fichier dans le répertoire public avant sa destruction
        try {
            $uploadedFile->move($this->getUploadCsvDir(), $newFileName);
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
        $fileStr = $this->getUploadCsvDir() . self::FILE_NAME;
        $handle = fopen($fileStr, 'r');
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $i++;
            $referentiel = new Referentiel();
            $referentiel
                ->setLibelle((string) $data[1])
                ->setTypeReferentiel((string) $data[2]);
            try {
                $this->entityManager->persist($referentiel);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                $errorInsert = "L'import des participants a échoué lors de la ligne n° " . $i . " (Mr/Mme " . (string) $data[3] . " " . (string) $data[5] . ").";
            }
        }
    }

    /**
     * Procédure de suppression du fichier CSV dans le répertoire "/public/files/"
     */
    public function deleteCsvFile() {
        unlink($this->getUploadCsvDir() . self::FILE_NAME);
    }
}
