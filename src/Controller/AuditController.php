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
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
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
        $tous_les_audits = $auditRepository->findAllAuditAllInformation();
        return $this->render('audit/audit_liste.html.twig', [
            'tous_les_audits' => $tous_les_audits
        ]);
    }

    /**
     * @Route("/audit/creation/{id}", name="audit_creation")
     */
    public function creationAudit(Request $request,
                                  EntityManagerInterface $entityManager,
                                  AuditRepository $auditRepository,
                                  StatutRepository $statutRepository)
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
            $audit->setStatut($statutRepository->findStatutByLibelle("En cours"));
            
            foreach($audit->getReferentiel()->getChapitres() as $chapitre){

                foreach($chapitre->getRecommandations() as $recommandation){
                    foreach($recommandation->getPointsControle() as $pointControle){
                        $audit_controle = new AuditControle();
                        $audit_controle->setPointControle($pointControle);
                        $audit_controle->setEstValide(false);
                        $audit_controle->setRecommandation($recommandation);
                        $entityManager->persist($audit_controle);
                        $audit->addAuditsControle($audit_controle);
                    }
                    $remarque = new Remarque();
                    $remarque->setRecommandation($recommandation);
                    $audit->addRemarque($remarque);
                }
            }
                       
            // Sauvegarde en Bdd
            $entityManager->persist($audit);
            $entityManager->flush();

            // On ajoute un message flash pour la création de l'audit
            $this->addFlash("link", "L'audit a été créé");

            // On redirige vers la première recommandation de l'audit
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
        //On récupère en bdd l'audit en fonction de l'id en paramètre
        $audit = $auditRepository->find($request->get('id'));

        //Création du formulaire de validation de l'audit
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
                                  AuditRepository $auditRepository,
                                  KernelInterface $kernel) :Response
    {

        $audit = $auditRepository->find($request->get('id'));

/* 
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
 */
        //On désactive la limite de la memory du php.ini pour passer le pdf
        //ini_set('memory_limit','-1');
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
            $url = "https"; 
        else
            $url = "http"; 
            
        // Ajoutez // à l'URL.
        $url .= "://"; 
            
        // Ajoutez l'hôte (nom de domaine, ip) à l'URL.
        $url .= $_SERVER['HTTP_HOST']; 


        //On définit des options du pdf
        $options = new Options();
        $options->set( 'isRemoteEnabled', TRUE );
        $options->set( 'isPhpEnabled', TRUE );
        
        
        // On instancie la classe DomPdf
        $dompdf = new Dompdf($options);
        //On définit le context du pdf
        $contxt = stream_context_create([
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'GET',
            ],
            'ssl' => [ 
                'verify_peer' => FALSE, 
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE,
            ] 
        ]);
        $dompdf->setHttpContext($contxt);

        //On génére la vue Twig qui sera utiisée pour l'export vers le fichier pdf
        $html = $this->renderView('pdf/genererPdfAudit.html.twig', [
            'audit' => $audit,
            'url' => $url
        ]);
        $html .= '<link type="text/css" href="'. $url .'/css/pdf.css" rel="stylesheet" />';
        // On charge le html dans domPdf
        $dompdf->loadHtml($html);

        // On définit le format et l'orientation du pdf
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dateAudit = $audit->getDateCreation()->format('d-m-Y');
        $nomAudit = $audit->getSociete()->getNom() . '_' . $dateAudit . '.pdf';
        //On range les données du PDF
        $output = $dompdf->output();

        $dompdf->stream($nomAudit, array("Attachment" => true));
        
        //On veut écrire le fichier pdf dans le directory public
        $publicDirectory = $kernel->getProjectDir() . '/public/pdf/audits';
        $pdfFilePath = $publicDirectory . '/' . $audit->getSociete()->getNom() . '_' . $dateAudit . '.pdf';

        //On écrit dans le chemin désiré
        file_put_contents($pdfFilePath, $output);

        // On rend le html en pdf
        $dompdf->render();

        //On redirige après le chargement du pdf
        return $this->redirectToRoute('audit_liste');
    }

    //FONCTION POUR VERIFIER LE RENDU DU PDF QUE L'ON VEUT EXPORTER (A SUPPRIMER QUAND L'EXPORT PDF EST FINI)
    /**
     * @Route("/audit/pdf/{id}", name="audit_pdf")
     */
    public function pdfAudit(Request $request,
                                  EntityManagerInterface $entityManager,
                                  AuditRepository $auditRepository,
                                  KernelInterface $kernel)
    {

        $audit = $auditRepository->find($request->get('id'));


        //$snappy = $this->get('knp_snappy.pdf');
        return $this->render('pdf/genererPdfAudit.html.twig', [
            'audit' => $audit
        ]);

    }
}
