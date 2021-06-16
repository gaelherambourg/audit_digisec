<?php

namespace App\Controller;

use App\Entity\Audit;
use App\Entity\AuditControle;
use App\Entity\Preuve;
use App\Entity\Recommandation;
use App\Entity\Referentiel;
use App\Entity\Remarque;
use App\Entity\Remediation;
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
                                   RecommandationRepository $recommandationRepository)
    {

        //On récupère l'id de l'audit
        $id = $request->get('id');
        //On requête en bdd pour récupérer l'audit associé à l'id
        $audit = $auditRepository->findAuditAllInformation($id);
        //On requête en bdd pour récupérer la recommandation grâce à l'id
        $recommandation = $entityManager->find(Recommandation::class, $id_recommandation);
        //On vérifie combien de recommandations existent pour ce référentiel
        $nbReco = $recommandationRepository->nbRecommandationByReferentieo($audit->getReferentiel()->getId());
        //On instancie une nouvelle liste d'audit_controle
        $listeAuditControle = new ArrayCollection();
        //On instancie une nouvelle preuve
        $preuve = new Preuve();
        //On récupère la remarque associé à l'audit en cours et à la recommandation en cours
        $remarque = $remarqueRepository->findByAuditAndRecommandation($id, $id_recommandation);
        //On remplit la liste d'audit_controles avec les points de controles équivalents au référentiel de l'audit en cours
        $listeAuditControle = $auditControleRepository->findAllPointControleByAuditAndRecommandation($id, $id_recommandation);
        
        $nbPointControle = 0;
        $nbPointControleValide = 0;
        foreach($listeAuditControle as $auditControle){
            if($auditControle->getEstValide() == true){
                $nbPointControleValide++;
            }
            $nbPointControle++;
        }
        $pourcentageValide = ($nbPointControleValide/$nbPointControle)*100;
        dump($nbPointControle);
        dump($nbPointControleValide);
         //Création du formulaire
        $audit_form_controle = $this->createForm(AuditPointControleType::class, ['audit_controle' => $listeAuditControle, 'remarque' => $remarque]);
        $preuve_form = $this->createForm(PreuveFormType::class, $preuve);

        $audit_form_controle->handleRequest($request);
        
        $preuves = $preuveRepository->findAll();
        $requete = $request->request->all();

        // Si le formulaire est soumis
        if ($audit_form_controle->isSubmitted() && $audit_form_controle->isValid()) {

            //On vérifie que tous les points de controles de la recommandation en cours aient au moins une preuve
            //Si ce n'est pas le cas on recharge la page en affichant un message d'erreur
            foreach($listeAuditControle as $auditControle){
                if($auditControle->getPreuves()->count() < 1){
                    $this->addFlash("danger", "Tous les points de contrôle doivent avoir au moins une preuve");
                    return $this->redirectToRoute('audit_controle', ['id' => $id, 'id_recommandation' => $id_recommandation]);
                }
            }

            // TODO : A AMELIORER  : on supprime les remediations lié à l'audit de controle et on boucle sur les résultats de la $requete
            // pour connaitre les remediations cochées
            foreach($listeAuditControle as $auditControle){
                foreach($auditControle->getRemediations() as $remediation){
                    $auditControle->removeRemediation($remediation);
                }
                $auditControle->setEstValide(true);
            }

            foreach($audit_form_controle->getData("[audit_controle]") as $audit_controle){
                foreach($audit_controle as $controle){
                    foreach($requete as $cle => $valeur){
                        if($valeur == $controle->getId()){
                            $controle->addRemediation($entityManager->find(Remediation::class, $cle));
                        }
                    }
                }
                
            }
            //FIN TODO A AMELIORER

            // Sauvegarde en Bdd
            $entityManager->persist($audit);
            $entityManager->flush();

            //A l'enregistrement de la recommandation, on vérifie si celle-ci est la dernière du référentiel, si oui, on passe à la validation de l'audit
            //sinon, on passe à la recommandation suivante
            if($id_recommandation < $nbReco){
                $id_suivant_recommandation = $id_recommandation + 1;
                // On redirige vers la recommandation suivante
                return $this->redirectToRoute('audit_controle', ['id' => $id, 'id_recommandation' => $id_suivant_recommandation]);

            }else{
                $listeAuditControles = $auditControleRepository->findAllPointControleByAudit($id);
                foreach($listeAuditControles as $auditControle){
                    if($auditControle->getEstValide() == false){
                        return $this->redirectToRoute('audit_controle', ['id' => $id, 'id_recommandation' => $auditControle->getRecommandation()->getId()]);
                    }  
                }
                //On redirige vers la liste d'audit si c'est la derniere recommandation de l'audit
                return $this->redirectToRoute('audit_validation', ['id' => $id]);
            }
        }

        return $this->render('audit_controle/audit_controle.html.twig', [
            'form_audit_controle' => $audit_form_controle->createView(),
            'preuveForm' => $preuve_form->createView(),
            'audit' => $audit,
            'preuves' => $preuves,
            'recommandation' => $recommandation,
            'listeAuditControle' => $listeAuditControle,
            'pourcentageValide' => $pourcentageValide
        ]);
    }
}
