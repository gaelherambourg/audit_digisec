<?php

namespace App\Controller;

use App\Model\CsvForm;
use App\Form\CsvFormType;
use App\Form\RechercheSimpleType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use App\Services\ImportCsvServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel/liste", name="referentiel_liste")
     */
    public function listerReferentiel(
        Request $request,
        ReferentielRepository $referentielRepository,
        ImportCsvServices $importCsvServices
    ): Response {
        
        //Création du formulaire de recherche
        $form = $this->createForm(RechercheSimpleType::class);
        $form->handleRequest($request);

        $referentiel_recherches = "";
        $recherche_utilisateur = "";

        // Gestion Formulaire d'import de plusieurs participants :
        $csvForm =  new CsvForm();
        $csvRegisterForm = $this->createForm(CsvFormType::class, $csvForm);
        $csvRegisterForm->handleRequest($request);

        //On récupère tous les référentiels en bdd
        $tous_les_referentiels = $referentielRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $recherche_utilisateur = $form->get('recherche')->getData();

            $referentiel_recherches = $referentielRepository->recherche($recherche_utilisateur);
            return $this->render('referentiel/referentiel_liste.html.twig', [
                'tous_les_referentiels' => $tous_les_referentiels,
                'baseVide' => 'Aucun référentiel ne correspond à votre recherche.',
                'referentiel_recherches' => $referentiel_recherches,
                'recherche_utilisateur' => $recherche_utilisateur,
                'form_recherche_referentiel' => $form->createView()
            ]);
        }

        // Soumission du formulaire de création d'un référentiel
        if ($csvRegisterForm->isSubmitted() && $csvRegisterForm->isValid()) {
            // On charge le fichier csv dans notre répertoire
            $isItUploaded = $importCsvServices->uploadCsvFile($csvRegisterForm);
            // on lit le fichier s'il est uploadé
            if ($isItUploaded) {
                dump('Le fichier a été importé');
                // on insert toutes les données en base
                $data = $importCsvServices->insertCsvFile();
                // on efface le fichier
                $importCsvServices->deleteCsvFile();
                // On ajoute un message flash
                $this->addFlash("info", "L'insertion a fonctionnée");
            }
        }

        return $this->render('referentiel/referentiel_liste.html.twig', [
            'tous_les_referentiels' => $tous_les_referentiels,
            'referentiel_recherches' => $referentiel_recherches,
            'recherche_utilisateur' => $recherche_utilisateur,
            'form_recherche_referentiel' => $form->createView(),
            'csvForm' => $csvRegisterForm->createView(),
        ]);
    }
}
