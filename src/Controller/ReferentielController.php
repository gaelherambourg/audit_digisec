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
        $tous_les_referentiels = $referentielRepository->findAll();

        dump($tous_les_referentiels);

        return $this->render('referentiel/referentiel_liste.html.twig', [
            'tous_les_referentiels' => $tous_les_referentiels
        ]);
    }
}
