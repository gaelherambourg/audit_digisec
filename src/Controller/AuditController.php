<?php

namespace App\Controller;

use App\Entity\Audit;
use App\Entity\AuditControle;
use App\Entity\Remarque;
use App\Entity\RemediationControle;
use App\Entity\Societe;
use App\Entity\Statut;
use App\Form\AuditType;
use App\Repository\AuditRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class AuditController extends AbstractController
{
    /**
     * @Route("/audit/liste", name="audit_liste")
     */
    public function listerAudit(Request $request,
                                EntityManagerInterface $entitymanager,
                                AuditRepository $auditRepository)
    {

        //On récupère la liste de tous les audits en bdd
        $tous_les_audits = $auditRepository->findAll();

        return $this->render('audit/audit_liste.html.twig', [
            'tous_les_audits' => $tous_les_audits
        ]);
    }

    /**
     * @Route("/audit/creation/{id}", name="audit_creation")
     */
    public function creationAudit(Request $request,
                                  EntityManagerInterface $entityManager,
                                  AuditRepository $auditRepository)
    {
        //On récupère l'id de la société sur laquelle on veut créer l'audit
        $id_societe = $request->get('id');

        //On récupère la société sur laquelle porte l'audit
        $societe_audit = $entityManager->find(Societe::class, $id_societe);

        //On créé une instance d'Audit
        $audit = new Audit();

        //On associe la société à l'audit
        $audit->setSociete($societe_audit);

        //Création du formulaire de recherche
        $audit_form = $this->createForm(AuditType::class, $audit);
        $audit_form->handleRequest($request);

        // Si le formulaire est soumis
        if ($audit_form->isSubmitted() && $audit_form->isValid()) {

            // On modifie les données vide de l'audit
            $audit->setDateCreation(new \DateTime());
            $audit->setNom($societe_audit->getNom()." - ".$audit->getReferentiel()->getLibelle()." - ".$audit->getDateCreation()->format('d/m/Y'));
            $audit->setStatut($entityManager->find(Statut::class, 1));
            
            foreach($audit->getReferentiel()->getChapitres() as $chapitre){

                foreach($chapitre->getRecommandations() as $recommandation){
                    foreach($recommandation->getPointsControle() as $pointControle){
                        $audit_controle = new AuditControle();
                        $audit_controle->setPointControle($pointControle);
                        $entityManager->persist($audit_controle);
                        $audit->addAuditsControle($audit_controle);
                    }
                    $remarque = new Remarque();
                    $remarque->setRecommandation($recommandation);
                    $audit->addRemarque($remarque);
                }
            }
            
            dump($audit->getAuditsControle());
           
            // Sauvegarde en Bdd
            $entityManager->persist($audit);
            $entityManager->flush();

            // On ajoute un message flash
            $this->addFlash("link", "L'audit a été créé");

            // On redirige vers societe_liste
            return $this->redirectToRoute('audit_controle', [
                'id' => $audit->getId(),
                'id_recommandation' => $audit->getReferentiel()->getChapitres()->first()->getRecommandations()->first()->getId()

            ]);
        }

        return $this->render('audit/audit_creation.html.twig', [
            'form_creation_audit' => $audit_form->createView(),
            'societe_audit' => $societe_audit
        ]);
    }
}
