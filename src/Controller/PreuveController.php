<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreuveController extends AbstractController
{
    /**
     * @Route("/preuve", name="preuve")
     */
    public function ajoutPreuve(): Response
    {
        return $this->render('preuve/index.html.twig', [
            'controller_name' => 'PreuveController',
        ]);
    }
}
