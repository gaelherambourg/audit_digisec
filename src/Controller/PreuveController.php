<?php

namespace App\Controller;

use App\Entity\Preuve;
use App\Form\PreuveFormType;
use App\Repository\AuditControleRepository;
use App\Repository\PreuveRepository;
use App\Services\ErreursServices;
use App\Services\FichierPreuveServices;
use App\Services\ImagePreuveServices;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreuveController extends AbstractController
{
    /**
     * @Route("/preuve/", name="preuve")
     */
    public function ajoutPreuve(Request $request,
                                PreuveRepository $preuveRepository,
                                AuditControleRepository $auditControleRepository,
                                ErreursServices $erreursServices,
                                ImagePreuveServices $imagePreuveServices,
                                FichierPreuveServices $fichierPreuveServices,
                                EntityManagerInterface $entityManager): Response
    {

        $resultat = "";
        
        // On créer une instance de Adresse
        $preuve = new Preuve();

        // On créer une instance de la classe de formulaire que l'on associe à notre formulaire
        $preuveForm = $this->createForm(PreuveFormType::class, $preuve);




        if ($request->getMethod() == 'POST') {

            $texte = $request->get('texte');
            $fichier = $request->files->get('fichier');
            $image = $request->files->get('image');
            

            
            //$preuveForm->handleRequest($request);
            
            $preuveForm->submit(array_merge(['texte' => $texte, 'fichier' => $fichier, 'image' => $image]), false);



            if ($preuveForm->isSubmitted()) {
                $resultat = "dans le isSubmitted";
                $test = $preuveForm->isValid();
                if ($preuveForm->isValid()) {
                    $resultat = 'success';

                    $auditControle = $auditControleRepository->find($request->get('auditControleId'));
                    $preuve->setAuditControle($auditControle);
                    $preuve->setDateCreation(new DateTime());

                    // On récupère l'image'et on utilise FichierPreuveServices pour l'enregistrement
                    $uploadedFile = $request->files->get('fichier');
                    if ($uploadedFile) {
                        $pictureFileName = $fichierPreuveServices->upload($uploadedFile);
                        $preuve->setFichier($pictureFileName);
                    }
                    $uploadedImage = $request->files->get('image');
                    if ($uploadedImage) {
                        $pictureFileName = $imagePreuveServices->upload($uploadedImage);
                        $preuve->setImage($pictureFileName);
                    }
                    $entityManager->persist($preuve);
                    $entityManager->flush();

                    // On ajoute un message flash
                    $this->addFlash("link", "La preuve a été ajoutée");
                    return new JsonResponse(['resultat' => $resultat, 'preuveForm' =>$preuveForm]);
                } else {
                    $erreurs = $erreursServices->getErrorMessages($preuveForm);
                    $resultat = 'On passe dans le else';
                }
            }
        }

        return new JsonResponse(['resultat' => $resultat, 'erreur' => $erreurs, 'test' => $test, 'preuveForm' =>$preuveForm]);
    }
}
