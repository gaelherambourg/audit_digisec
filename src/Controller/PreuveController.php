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
            $token = $request->get('token');
            
            $preuveForm->submit(array_merge(['texte' => $texte, 'fichier' => $fichier, 'image' => $image, '_token' => $token]), false);

            if ($preuveForm->isSubmitted()) {

                if ($preuveForm->isValid()) {

                    $resultat = 'success';

                    //On récupère l'audit Controle associé à la preuve 
                    $auditControle = $auditControleRepository->find($request->get('auditControleId'));

                    //On hydrate le reste de l'entité preuve
                    $preuve->setAuditControle($auditControle);
                    $preuve->setDateCreation(new DateTime());

                    // On récupère l'image'et on utilise FichierPreuveServices pour l'enregistrement du fichier
                    $uploadedFile = $request->files->get('fichier');
                    if ($uploadedFile) {
                        $pictureFileName = $fichierPreuveServices->upload($uploadedFile);
                        $preuve->setFichier($pictureFileName);
                    }
                    // On récupère l'image'et on utilise ImagePreuveServices pour l'enregistrement de l'image
                    $uploadedImage = $request->files->get('image');
                    if ($uploadedImage) {
                        $pictureFileName = $imagePreuveServices->upload($uploadedImage);
                        $preuve->setImage($pictureFileName);
                    }

                    //On persiste l'entité
                    $entityManager->persist($preuve);

                    //On enregistre la preuve en bdd
                    $entityManager->flush();

                    // On ajoute un message flash pour préciser à l'utilisateur à bien été ajouté
                    $this->addFlash("link", "La preuve a été ajoutée");

                    //On renvoie la réponse Json pour le traitement dans la fonction ajoutPreuve dans preuve.js
                    return new JsonResponse(['resultat' => $resultat]);
                } else {

                    //Si le formulaire soumis n'est pas valide, on récupère les messages d'erreurs que l'on transmet pour affichage
                    $erreurs = $erreursServices->getErrorMessages($preuveForm);
                }
            }
        }

        return new JsonResponse(['resultat' => $resultat, 'erreur' => $erreurs]);
    }
}
