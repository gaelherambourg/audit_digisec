<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Contact;
use App\Entity\Societe;
use App\Form\AdresseFormType;
use App\Form\ContactFormType;
use App\Form\SocieteFormType;
use App\Services\LogoServices;
use App\Form\AjoutSocieteFormType;
use App\Form\ModifierSocieteFormType;
use App\Form\SearchSocieteType;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SocieteController extends AbstractController
{
    /**
     * @Route("/societe/ajouter", name="societe_ajouter")
     */
    public function societeAjouter(Request $request, EntityManagerInterface $entityManager, LogoServices $logoServices): Response
    {
        // On créé une instance de société, Adresse, et Contact
        $societe = new Societe();
        $adresse = new Adresse();
        $contact = new Contact();

        // Crée une instance de la classe de formulaire que l'on associe à notre formulaire
        $societeForm = $this->createForm(AjoutSocieteFormType::class, ['societe' => $societe, 'adresse' => $adresse, 'contact' => $contact]);

        // On prend les données du formulaire soumis, et les injecte dans $societe
        $societeForm->handleRequest($request);

        // Si le formulaire est soumis
        if ($societeForm->isSubmitted() && $societeForm->isValid()) {

            // On récupère les données de l'adresse
            $adresse->setLibelle('Adresse principale');

            // On modifie les données vide de la société
            $societe->setDateCreation(new \DateTime());
            $societe->setEstDigisec(false);
            $societe->addAdresse($adresse);
            $societe->addContact($contact);

            // On récupère le logo et on utilise LogoServices pour l'enregistrement
            $uploadedFile = $societeForm->get('societe')->get('logo')->getData();
            if ($uploadedFile) {
                $pictureFileName = $logoServices->upload($uploadedFile);
                $societe->setLogo($pictureFileName);
            }

            // Sauvegarde en Bdd
            $entityManager->persist($societe);
            $entityManager->flush();

            // On ajoute un message flash
            $this->addFlash("link", "L'entreprise a été ajoutée");

            // On redirige vers societe_liste
            return $this->redirectToRoute('societe_liste');
        }

        return $this->render('societe/societe_ajouter.html.twig', [
            "societeForm" => $societeForm->createView(),
        ]);
    }

    /**
     * @Route("/societe/liste", name="societe_liste")
     */
    public function listerSociete(Request $request,
                                  EntityManagerInterface $entityManager,
                                  SocieteRepository $societeRepository)
    {

        //Création du formulaire de recherche
        $form = $this->createForm(SearchSocieteType::class);
        $form->handleRequest($request);

        $societes_recherchees = "";
        $recherche_utilisateur = "";

        //On récupère toutes les sociétés en bdd
        $toutes_les_societes = $societeRepository->findAll();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $recherche_utilisateur = $form->get('recherche')->getData();

            $societes_recherchees =$societeRepository->recherche($recherche_utilisateur);
            return $this->render('societe/societe_liste.html.twig',[
                'toutes_les_societes' => $toutes_les_societes,
                'baseVide'=> 'Aucune société ne correspond à votre recherche.',
                'societes_recherchees'=> $societes_recherchees,
                'recherche_utilisateur' => $recherche_utilisateur,
                'form_recherche_societe' => $form->createView()
            ]);
        }


        
            
        return $this->render('societe/societe_liste.html.twig', [
            'toutes_les_societes' => $toutes_les_societes,
            'societes_recherchees'=> $societes_recherchees,
            'recherche_utilisateur' => $recherche_utilisateur,
            'form_recherche_societe' => $form->createView()
        ]);
    }

    /**
     * @Route("/societe/modifier/{id}", name="societe_modifier", requirements={"id"="\d+"})
     */
    public function societeModifier($id, Request $request, EntityManagerInterface $entityManager, LogoServices $logoServices, SocieteRepository $societeRepository): Response
    {
        // On créé une instance de société, Adresse, et Contact
        $societe = $societeRepository->findAllInformationsBySociety($id);
        $logo = $societe->getLogo();
        $contact = new Contact();
        $adresse = new Adresse();

        // Crée une instance de la classe de formulaire que l'on associe à notre formulaire
        $societeForm = $this->createForm(ModifierSocieteFormType::class, ['societe' => $societe, 'adresse' => $societe->getAdresse(), 'contact' => $societe->getContact()]);
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $adresseForm = $this->createForm(AdresseFormType::class, $adresse);

        // On prend les données du formulaire soumis, et les injecte dans $societe
        $societeForm->handleRequest($request);
        $contactForm->handleRequest($request);
        $adresseForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact->setSociete($societe);
            $entityManager->persist($contact);
            $entityManager->flush();
            // On ajoute un message flash
            $this->addFlash("link", "Le contact a été ajouté");

            return $this->redirect($request->getUri());
        }

        if ($adresseForm->isSubmitted() && $adresseForm->isValid()) {
            $adresse->setSociete($societe);
            $entityManager->persist($adresse);
            $entityManager->flush();
            // On ajoute un message flash
            $this->addFlash("link", "L'adresse a été ajouté");

            return $this->redirect($request->getUri());
        }

        // Si le formulaire est soumis
        if ($societeForm->isSubmitted() && $societeForm->isValid()) {

            // On récupère le logo et on utilise LogoServices pour l'enregistrement
            $uploadedFile = $societeForm->get('societe')->get('logo')->getData();
            if ($uploadedFile) {
                $pictureFileName = $logoServices->upload($uploadedFile);
                $societe->setLogo($pictureFileName);
            }

            // On ajoute la date de modification
            $societe->setDateModification(new \DateTime());

            // Sauvegarde en Bdd
            $entityManager->persist($societe);
            $entityManager->flush();

            // On ajoute un message flash
            $this->addFlash("link", "L'entreprise a été modifée");

            // On redirige vers societe_liste
            return $this->redirectToRoute('societe_liste');
        }

        return $this->render('societe/societe_modifier.html.twig', [
            "societeForm" => $societeForm->createView(),
            "contactForm" => $contactForm->createView(),
            "adresseForm" => $adresseForm->createView(),
            "logo" => $logo
        ]);
    }
}
