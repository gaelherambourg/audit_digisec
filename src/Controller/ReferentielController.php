<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel/liste", name="referentiel_liste")
     */
    public function listerReferentiel()
    {
        return $this->render('referentiel/referentiel_liste.html.twig', [
        ]);
    }
}
