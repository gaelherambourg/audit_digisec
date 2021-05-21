<?php

namespace App\Controller;

use App\Form\RechercheSimpleType;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel/liste", name="referentiel_liste")
     */
    public function listerReferentiel(Request $request,
                                      EntityManagerInterface $entitymanager,
                                      ReferentielRepository $referentielRepository)
    {
        //Création du formulaire de recherche
        $form = $this->createForm(RechercheSimpleType::class);
        $form->handleRequest($request);

        $referentiel_recherches = "";
        $recherche_utilisateur = "";

        //On récupère tous les référentiels en bdd
        $tous_les_referentiels = $referentielRepository->findAll();

        if($form->isSubmitted() && $form->isValid())
        {
            $recherche_utilisateur = $form->get('recherche')->getData();

            $referentiel_recherches =$referentielRepository->recherche($recherche_utilisateur);
            return $this->render('referentiel/referentiel_liste.html.twig',[
                'tous_les_referentiels' => $tous_les_referentiels,
                'baseVide'=> 'Aucun référentiel ne correspond à votre recherche.',
                'referentiel_recherches'=> $referentiel_recherches,
                'recherche_utilisateur' => $recherche_utilisateur,
                'form_recherche_referentiel' => $form->createView()
            ]);
        }

        

        return $this->render('referentiel/referentiel_liste.html.twig', [
            'tous_les_referentiels' => $tous_les_referentiels,
            'referentiel_recherches'=> $referentiel_recherches,
            'recherche_utilisateur' => $recherche_utilisateur,
            'form_recherche_referentiel' => $form->createView()
        ]);
    }
}
