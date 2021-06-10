<?php

namespace App\Controller;

use App\Entity\Audit;
use App\Entity\AuditControle;
use App\Entity\Remarque;
use App\Entity\RemediationControle;
use App\Entity\Societe;
use App\Entity\Statut;
use App\Form\AuditType;
use App\Form\ValidationAuditFormType;
use App\Kernel;
use App\Repository\AuditRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpKernel\KernelInterface;

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

     /**
     * @Route("/audit/validation/{id}", name="audit_validation")
     */
    public function validationAudit(Request $request,
                                  EntityManagerInterface $entityManager,
                                  AuditRepository $auditRepository)
    {

        $audit = $auditRepository->find($request->get('id'));

        //Création du formulaire de recherche
        $audit_validation_form = $this->createForm(ValidationAuditFormType::class, $audit);

        $audit_validation_form->handleRequest($request);

        // Si le formulaire est soumis
        if ($audit_validation_form->isSubmitted() && $audit_validation_form->isValid()) {

           
            // Sauvegarde en Bdd
            $entityManager->persist($audit);
            $entityManager->flush();

            // On ajoute un message flash
            $this->addFlash("link", "L'audit a été validé");

            // On redirige vers audit_liste
            return $this->redirectToRoute('generer_audit_pdf', ['id' => $audit->getId()]);
        }

        return $this->render('audit/audit_validation.html.twig', [
            'form_validation_audit' => $audit_validation_form->createView(),
            'audit' => $audit
        ]);
    }

    /**
     * @Route("/audit/genererPdf/{id}", name="generer_audit_pdf")
     */
    public function genererPdfAudit(Request $request,
                                  EntityManagerInterface $entityManager,
                                  AuditRepository $auditRepository,
                                  KernelInterface $kernel)
    {

        $audit = $auditRepository->find($request->get('id'));

        ini_set('memory_limit','-1');
        $options = new Options();
        $options->set( 'isRemoteEnabled', TRUE );

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);


        $contxt = stream_context_create([
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'GET',
                'user_agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
            ],
            'ssl' => [ 
                'verify_peer' => FALSE, 
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE,
            ] 
        ]);
        $dompdf->setHttpContext($contxt);

        $html = $this->renderView('pdf/genererPdfAudit.html.twig', [
            'audit' => $audit
        ]);
        // instantiate and use the dompdf class
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        //On range les données du PDF
        $output = $dompdf->output();
        dump($output);
        //On veut écrire le fichier pdf dans le directory public
        $publicDirectory = $kernel->getProjectDir() . '/public/pdf/audits';
        $pdfFilePath = $publicDirectory . '/' . $audit->getId() . '.pdf';

        //$pdfFilePath = $publicDirectory . '/' . 'de' .'.pdf';

        //On écrit dans le chemin désiré
        file_put_contents($pdfFilePath, $output);

        /* // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("mypdf.pdf",[
            "Attachment" => true
        ]); */

        ini_set('memory_limit','256MB');
        return $this->redirectToRoute('audit_validation', ['id' => 7]);
    }
}
