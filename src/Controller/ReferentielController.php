<?php

namespace App\Controller;

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
        //On récupère tous les référentiels en bdd
        $tous_les_referentiels = $referentielRepository->findAll();

        return $this->render('referentiel/referentiel_liste.html.twig', [
            'tous_les_referentiels' => $tous_les_referentiels
        ]);
    }
}
