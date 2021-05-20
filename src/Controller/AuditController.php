<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuditController extends AbstractController
{
    /**
     * @Route("/audit/liste", name="audit_liste")
     */
    public function listerAudit()
    {
        return $this->render('audit/audit_liste.html.twig', [
        ]);
    }
}
