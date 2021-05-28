<?php

namespace App\Controller;

use App\Entity\Audit;
use App\Entity\AuditControle;
use App\Entity\Recommandation;
use App\Entity\Referentiel;
use App\Form\AuditControlFormType;
use App\Form\AuditPointControleType;
use App\Repository\AuditControleRepository;
use App\Repository\AuditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuditControleController extends AbstractController
{
    /**
     * @Route("/audit/controle/{id}/{id_recommandation}", name="audit_controle")
     */
    public function audit_controle(Request $request,
                                   EntityManagerInterface $entityManager,
                                   $id,
                                   $id_recommandation,
                                   AuditControleRepository $auditControleRepository,
                                   AuditRepository $auditRepository)
    {
        //$audit = $auditControleRepository->findById($id);
        $audit = $auditRepository->find($id);
        $recommandation = $entityManager->find(Recommandation::class, $id_recommandation);

        $auditControle = new AuditControle();
        $listeAuditControle = new ArrayCollection();
        dump($audit);
        // On créer une liste de point de controle correspondant à la recommandation ($id_recommandation)
        /* foreach($audit->getAuditsControle() as $audit_controle){
            if($audit_controle->getpointControle()->getRecommandation()->getId() == $id_recommandation){
                $listeAuditControle->add($audit_controle);
            }
        } */
        $listeAuditControle = $auditControleRepository->findAllPointControleByAuditAndRecommandation($id, $id_recommandation);
         //Création du formulaire
        $audit_form_controle = $this->createForm(AuditPointControleType::class, ['audit_controle' => $listeAuditControle]);
        $audit_controle_form = $this->createForm(AuditControlFormType::class, $auditControle);

        $audit_form_controle->handleRequest($request);
        $audit_controle_form->handleRequest($request);


        if ($audit_controle_form->isSubmitted() && $audit_controle_form->isValid()) {
            $auditControle->setAudit($audit);
            $entityManager->persist($auditControle);
            $entityManager->flush();

            dump($auditControle);
            return $this->redirectToRoute('societe_liste');
        }

        // Si le formulaire est soumis
        if ($audit_form_controle->isSubmitted() && $audit_form_controle->isValid()) {

            
            // Sauvegarde en Bdd
            $entityManager->persist($audit);
            $entityManager->flush();

            // On redirige vers societe_liste
            return $this->redirectToRoute('societe_liste');
        }

        return $this->render('audit_controle/audit_controle.html.twig', [
            'form_audit_controle' => $audit_form_controle->createView(),
            'audit_controle_form' => $audit_controle_form->createView(),
            'audit' => $audit,
            'recommandation' => $recommandation
        ]);
    }
}
