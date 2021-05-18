<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel_liste", name="referentiel_liste")
     */
    public function index(): Response
    {
        return $this->render('referentiel/referentiel_liste.html.twig', [
        ]);
    }
}
