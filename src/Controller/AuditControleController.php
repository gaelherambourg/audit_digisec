<?php

namespace App\Controller;

use App\Entity\Audit;
use App\Entity\AuditControle;
use App\Entity\Preuve;
use App\Entity\Recommandation;
use App\Entity\Referentiel;
use App\Entity\Remarque;
use App\Form\AuditControlFormType;
use App\Form\AuditPointControleType;
use App\Form\PreuveFormType;
use App\Form\RemarqueRecommandationFormType;
use App\Repository\AuditControleRepository;
use App\Repository\AuditRepository;
use App\Repository\PreuveRepository;
use App\Repository\RecommandationRepository;
use App\Repository\RemarqueRepository;
use App\Services\FichierPreuveServices;
use App\Services\ImagePreuveServices;
use App\Services\LogoServices;
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
                                   $id_recommandation,
                                   AuditControleRepository $auditControleRepository,
                                   AuditRepository $auditRepository,
                                   PreuveRepository $preuveRepository,
                                   RemarqueRepository $remarqueRepository,
                                   RecommandationRepository $recommandationRepository,
                                   ImagePreuveServices $imagePreuveServices,
                                   FichierPreuveServices $fichierPreuveServices)
    {

        $id = $request->get('id');
        $audit = $auditRepository->find($id);
        $recommandation = $entityManager->find(Recommandation::class, $id_recommandation);
        $nbReco = $recommandationRepository->nbRecommandationByReferentieo($audit->getReferentiel()->getId());
        $auditControle = new AuditControle();
        $listeAuditControle = new ArrayCollection();
        $preuve = new Preuve();
        $remarque = $remarqueRepository->findByAuditAndRecommandation($id, $id_recommandation);
        
        $listeAuditControle = $auditControleRepository->findAllPointControleByAuditAndRecommandation($id, $id_recommandation);
        dump($listeAuditControle);
         //Création des formulaires
        $audit_form_controle = $this->createForm(AuditPointControleType::class, ['audit_controle' => $listeAuditControle, 'remarque' => $remarque]);
        //$audit_controle_form = $this->createForm(AuditControlFormType::class, $auditControle);
        
        $preuve_form = $this->createForm(PreuveFormType::class, $preuve);

        $audit_form_controle->handleRequest($request);
        //$audit_controle_form->handleRequest($request);
        //$preuve_form->handleRequest($request);
        
        $preuves = $preuveRepository->findAll();

        // Si le formulaire est soumis
        if ($audit_form_controle->isSubmitted() && $audit_form_controle->isValid()) {

            // Sauvegarde en Bdd
            $entityManager->persist($audit);
            
            $entityManager->flush();

            if($id_recommandation < $nbReco){
                $id_suivant_recommandation = $id_recommandation + 1;
                // On redirige vers la recommandation suivante
                return $this->redirectToRoute('audit_controle', ['id' => $id, 'id_recommandation' => $id_suivant_recommandation]);

            }else{
                //On redirige vers la liste d'audit si c'est la derniere recommandation de l'audit
                return $this->redirectToRoute('audit_liste');
            }
        }

        return $this->render('audit_controle/audit_controle.html.twig', [
            'form_audit_controle' => $audit_form_controle->createView(),
            'preuveForm' => $preuve_form->createView(),
            'audit' => $audit,
            'preuves' => $preuves,
            'recommandation' => $recommandation,
            'listeAuditControle' => $listeAuditControle
        ]);
    }
}
