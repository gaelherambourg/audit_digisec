<?php

namespace App\Controller;

use App\Entity\Recommandation;
use App\Entity\Referentiel;
use App\Model\CsvForm;
use App\Form\CsvFormType;
use App\Form\ModifierReferentielFormType;
use App\Form\ModifierSocieteFormType;
use App\Form\RechercheSimpleType;
use App\Form\ReferentielFormType;
use App\Services\ErreursServices;
use App\Services\ImportCsvServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel/liste/", name="referentiel_liste")
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
                // on insert toutes les données en base
                $data = $importCsvServices->insertCsvFile();
                // on efface le fichier
                $importCsvServices->deleteCsvFile();
                if ($data['errorInsert'] != "") {
                    $this->addFlash("warning", $data['errorInsert']);
                } else {
                    // On ajoute un message flash
                    $this->addFlash("info", "L'insertion a fonctionnée");
                }
            } else {
                $this->addFlash("danger", "Le téléchargement a échoué.");
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

    /**
     * @Route("/referentiel/liste/csv/", name="referentiel_liste_import")
     */
    public function importCsv(
        Request $request,
        ErreursServices $erreursServices,
        ImportCsvServices $importCsvServices,
        EntityManagerInterface $entityManager
    ): Response {

        $resultat = "";

        // On créer une instance de csvForm
        $csv = new CsvForm();

        // On créer une instance de la classe de formulaire que l'on associe à notre formulaire
        $csvForm = $this->createForm(CsvFormType::class, $csv);

        if ($request->getMethod() == 'POST') {

            $referentiel = $request->files->get('referentielCsv');
            $chapitre = $request->files->get('chapitreCsv');
            $recommandation = $request->files->get('recommandationCsv');
            $pointControle = $request->files->get('pointControleCsv');
            $remediation = $request->files->get('remediationCsv');
            $token = $request->get('token');

            // On prend les données du formulaire soumis, et les injecte dans $societe
            $csvForm->submit(array_merge(['referentielCsv' => $referentiel, 'chapitreCsv' => $chapitre, 'recommandationCsv' => $recommandation, 'pointControleCsv' => $pointControle, 'remediationCsv' => $remediation, '_token' => $token]), false);

            if ($csvForm->isSubmitted()) {
                if ($csvForm->isValid()) {
                    $resultat = 'success';

                    // On charge le fichier csv dans notre répertoire
                    $isItUploaded = $importCsvServices->uploadCsvFile($csvForm);
                    // on lit le fichier s'il est uploadé
                    if ($isItUploaded) {
                        // on insert toutes les données en base
                        $data = $importCsvServices->insertCsvFile();
                        // on efface le fichier
                        $importCsvServices->deleteCsvFile();
                        if ($data['errorInsert'] != "") {
                            $this->addFlash("warning", $data['errorInsert']);
                        } else {
                            // On ajoute un message flash
                            $this->addFlash("info", "L'insertion a fonctionnée");
                        }
                    } else {
                        $this->addFlash("danger", "Le téléchargement a échoué.");
                    }
                    // On retourne la réponse JSON
                    return new JsonResponse(['resultat' => $resultat]);
                } else {
                    // On retourne le tableau des erreurs de validation
                    $erreurs = $erreursServices->getErrorMessages($csvForm);
                }
            }
        }
        // On retourne la réponse JSON
        return new JsonResponse(['resultat' => $resultat, 'erreur' => $erreurs]);
    }

    /**
     * Modifie les informations d'une societe
     * @Route("/referentiel/modifier/{id}", name="referentiel_modifier", requirements={"id"="\d+"})
     */
    public function referentielModifier(
        $id,
        Request $request,
        ReferentielRepository $referentielRepository,
        EntityManagerInterface $entityManager
    ): Response {
        
        //On récupère l'id du référentiel
        $id = $request->get('id');

        $infoReferentiel = $referentielRepository->allInformation($id);
        
        $chapitres = $infoReferentiel->getChapitres();

        $recommandations = new ArrayCollection();
        foreach($chapitres as $chapitre){
            foreach($chapitre->getRecommandations() as $reco){
                $recommandations->add($reco);
            }
        }

        $referentielForm = $this->createForm(ModifierReferentielFormType::class, ['referentiel' => $infoReferentiel,
         'chapitre' => $chapitres, 'recommandation' => $recommandations
        ]);

        $referentielForm->handleRequest($request);

        // On affiche le Twig avec les différents formulaires
        return $this->render('referentiel/referentiel_modifier.html.twig', [
            'referentielForm' => $referentielForm->createView()
        ]);
    }
}
