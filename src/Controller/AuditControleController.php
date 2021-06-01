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
                                   $id,
                                   $id_recommandation,
                                   AuditControleRepository $auditControleRepository,
                                   AuditRepository $auditRepository,
                                   PreuveRepository $preuveRepository,
                                   RemarqueRepository $remarqueRepository,
                                   RecommandationRepository $recommandationRepository,
                                   ImagePreuveServices $imagePreuveServices,
                                   FichierPreuveServices $fichierPreuveServices)
    {
        //$audit = $auditControleRepository->findById($id);
        $audit = $auditRepository->find($id);
        $recommandation = $entityManager->find(Recommandation::class, $id_recommandation);
        $nbReco = $recommandationRepository->nbRecommandationByReferentieo($audit->getReferentiel()->getId());
        $auditControle = new AuditControle();
        $listeAuditControle = new ArrayCollection();
        $preuve = new Preuve();
        $remarque = $remarqueRepository->findByAuditAndRecommandation($id, $id_recommandation);
        
        // On créer une liste de point de controle correspondant à la recommandation ($id_recommandation)  == 290 requetes avec le foreach
        /* foreach($audit->getAuditsControle() as $audit_controle){
            if($audit_controle->getpointControle()->getRecommandation()->getId() == $id_recommandation){
                $listeAuditControle->add($audit_controle);
            }
        } */

        $listeAuditControle = $auditControleRepository->findAllPointControleByAuditAndRecommandation($id, $id_recommandation);

         //Création des formulaires
        $audit_form_controle = $this->createForm(AuditPointControleType::class, ['audit_controle' => $listeAuditControle, 'remarque' => $remarque]);
        //$audit_controle_form = $this->createForm(AuditControlFormType::class, $auditControle);
        $preuve_form = $this->createForm(PreuveFormType::class, $preuve);

        $audit_form_controle->handleRequest($request);
        //$audit_controle_form->handleRequest($request);
        $preuve_form->handleRequest($request);
        
        $preuves = $preuveRepository->findAll();

        if ($preuve_form->isSubmitted() && $preuve_form->isValid()) {

            $testAuditControle = $auditControleRepository->find($request->get('modPreuv'));
            $preuve->setAuditControle($testAuditControle);
            $preuve->setDateCreation(new \DateTime());

            // On récupère l'image'et on utilise LogoServices pour l'enregistrement
            $uploadedFile = $preuve_form->get('fichier')->getData();
            if ($uploadedFile) {
                $pictureFileName = $fichierPreuveServices->upload($uploadedFile);
                $preuve->setFichier($pictureFileName);
            }
            $uploadedImage = $preuve_form->get('image')->getData();
            if ($uploadedImage) {
                $pictureFileName = $imagePreuveServices->upload($uploadedImage);
                $preuve->setImage($pictureFileName);
            }
            $entityManager->persist($preuve);
            $entityManager->flush();

            return $this->redirectToRoute('audit_controle', ['id' => $id, 'id_recommandation' => $id_recommandation]);
        }
                

        /* if ($remarque_form->isSubmitted() && $remarque_form->isValid()) {
            $remarque->setAudit($audit);
            $remarque->setRecommandation($recommandation);
            $entityManager->persist($remarque);
            $entityManager->flush();
            
            return $this->redirectToRoute('audit_liste');
        } */

        /* if ($audit_controle_form->isSubmitted() && $audit_controle_form->isValid()) {
            $auditControle->setAudit($audit);
            $entityManager->persist($auditControle);
            $entityManager->flush();
            dump($remarque);
            return $this->redirectToRoute('referentiel_liste');
        } */
        
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
            'recommandation' => $recommandation
        ]);
    }
}
