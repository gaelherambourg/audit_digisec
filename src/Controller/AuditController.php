<?php

namespace App\Controller;

use App\Form\AuditType;
use App\Repository\AuditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/audit/creation", name="audit_creation")
     */
    public function creationAudit(Request $request,
                                  EntityManagerInterface $entitymanager,
                                  AuditRepository $auditRepository)
    {

        //Création du formulaire de recherche
        $form = $this->createForm(AuditType::class);
        $form->handleRequest($request);

        return $this->render('audit/audit_creation.html.twig', [
            'form_creation_audit' => $form->createView()
        ]);
    }
}
