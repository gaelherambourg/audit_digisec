<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Contact;
use App\Entity\Societe;
use App\Form\AdresseFormType;
use App\Form\ContactFormType;
use App\Services\LogoServices;
use App\Form\RechercheSimpleType;
use App\Services\ErreursServices;
use App\Form\AjoutSocieteFormType;
use App\Form\ModifierSocieteFormType;
use App\Repository\AdresseRepository;
use App\Repository\ContactRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SocieteController extends AbstractController
{
    /**
     * Ajoute une société
     * @Route("/societe/ajouter", name="societe_ajouter")
     */
    public function societeAjouter(
        Request $request,
        EntityManagerInterface $entityManager,
        LogoServices $logoServices
    ): Response {

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

        // On retourne la page Twig avec le formulaire
        return $this->render('societe/societe_ajouter.html.twig', [
            "societeForm" => $societeForm->createView(),
        ]);
    }

    /**
     * Affiche la liste des sociétés
     * @Route("/societe/liste", name="societe_liste")
     */
    public function listerSociete(
        Request $request,
        SocieteRepository $societeRepository
    ): Response {

        //Création du formulaire de recherche
        $form = $this->createForm(RechercheSimpleType::class);
        $form->handleRequest($request);

        $societes_recherchees = "";
        $recherche_utilisateur = "";

        //On récupère toutes les sociétés en bdd
        $toutes_les_societes = $societeRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $recherche_utilisateur = $form->get('recherche')->getData();

            $societes_recherchees = $societeRepository->recherche($recherche_utilisateur);
            return $this->render('societe/societe_liste.html.twig', [
                'toutes_les_societes' => $toutes_les_societes,
                'baseVide' => 'Aucune société ne correspond à votre recherche.',
                'societes_recherchees' => $societes_recherchees,
                'recherche_utilisateur' => $recherche_utilisateur,
                'form_recherche_societe' => $form->createView()
            ]);
        }

        return $this->render('societe/societe_liste.html.twig', [
            'toutes_les_societes' => $toutes_les_societes,
            'societes_recherchees' => $societes_recherchees,
            'recherche_utilisateur' => $recherche_utilisateur,
            'form_recherche_societe' => $form->createView()
        ]);
    }

    /**
     * Modifie les informations d'une societe
     * @Route("/societe/modifier/{id}", name="societe_modifier", requirements={"id"="\d+"})
     */
    public function societeModifier(
        $id,
        Request $request,
        EntityManagerInterface $entityManager,
        LogoServices $logoServices,
        SocieteRepository $societeRepository
    ): Response {

        // On créé une instance de société, Adresse, et Contact
        $societe = $societeRepository->findAllInformationsBySociety($id);
        $logo = $societe->getLogo();
        $contact = new Contact();
        $adresse = new Adresse();
        // On récupère les adresses et contacts de la societe
        $idAdresse = $societe->getAdresse();
        $idContact = $societe->getContact();

        // Crée une instance de la classe de formulaire que l'on associe à notre formulaire
        $societeForm = $this->createForm(ModifierSocieteFormType::class, ['societe' => $societe, 'adresse' => $societe->getAdresse(), 'contact' => $societe->getContact()]);
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $adresseForm = $this->createForm(AdresseFormType::class, $adresse);

        // On prend les données du formulaire soumis, et les injecte dans $societe
        $societeForm->handleRequest($request);

        // Si le formulaire est soumis
        if ($societeForm->isSubmitted()) {
            // Si le formulaire est valide
            if ($societeForm->isValid()) {

                // On vérifie si un logo existe déjà pour le supprimer
                if ($societe->getLogo()) {
                    $logoServices->deletePhoto($societe->getLogo());
                }
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
        }
        // On affiche le Twig avec les différents formulaires
        return $this->render('societe/societe_modifier.html.twig', [
            "societeForm" => $societeForm->createView(),
            "contactForm" => $contactForm->createView(),
            "adresseForm" => $adresseForm->createView(),
            "logo" => $logo,
            "id" => $id,
            "idAdresse" => $idAdresse,
            "idContact" => $idContact,
        ]);
    }

    /**
     * Ajout d'une adresse secondaire à une societe
     * @Route("/societe/modifier/adresse/{json}", name="societe_modifier_adresse")
     */
    public function ajoutAdresse(
        $json,
        ErreursServices $erreursServices,
        Request $request,
        EntityManagerInterface $entityManager,
        SocieteRepository $societeRepository
    ): Response {

        $resultat = "";

        // On récupère le JSON et on le décode
        $nouvelleAdresse = json_decode($json);
        $libelle = $nouvelleAdresse->libelle;
        $rue = $nouvelleAdresse->rue;
        $codePostal = $nouvelleAdresse->codePostal;
        $ville = $nouvelleAdresse->ville;
        $societeId = $nouvelleAdresse->societeId;
        $token = $nouvelleAdresse->formAdresseToken;

        // On récupère la societe pour l'associer à l'adresse
        $societe = $societeRepository->find($societeId);

        // On créer une instance de Adresse
        $adresse = new Adresse();

        // On hydrate l'objet
        $adresse->setLibelle($libelle);
        $adresse->setRue($rue);
        $adresse->setCodePostal($codePostal);
        $adresse->setVille($ville);
        $adresse->setSociete($societe);

        // On créer une instance de la classe de formulaire que l'on associe à notre formulaire
        $adresseForm = $this->createForm(AdresseFormType::class, $adresse);

        if ($request->getMethod() == 'POST') {

            // On prend les données du formulaire soumis, et les injecte dans $societe
            $adresseForm->submit(array_merge(['libelle' => $libelle, 'rue' => $rue, 'ville' => $ville, 'code_postal' => $codePostal, '_token' => $token], $request->request->all()), false);

            if ($adresseForm->isSubmitted()) {
                if ($adresseForm->isValid()) {
                    $resultat = 'success';

                    // Sauvegarde en Bdd
                    $entityManager->persist($adresse);
                    $entityManager->flush();

                    // On ajoute un message flash
                    $this->addFlash("link", "L'adresse a été ajouté");

                    // On retourne la réponse JSON
                    return new JsonResponse(['resultat' => $resultat]);
                } else {
                    // On retourne le tableau des erreurs de validation
                    $erreurs = $erreursServices->getErrorMessages($adresseForm);
                }
            }
        }
        // On retourne la réponse JSON
        return new JsonResponse(['resultat' => $resultat, 'erreur' => $erreurs]);
    }

    /**
     * Ajout d'un contact secondaire à une société
     * @Route("/societe/modifier/contact/{json}", name="societe_modifier_contact")
     */
    public function ajoutContact(
        $json,
        ErreursServices $erreursServices,
        Request $request,
        EntityManagerInterface $entityManager,
        SocieteRepository $societeRepository
    ): Response {

        $resultat = "";

        // On récupère le JSON et on le décode
        $nouveauContact = json_decode($json);
        $nom = $nouveauContact->nom;
        $prenom = $nouveauContact->prenom;
        $telephone = $nouveauContact->telephone;
        $mail = $nouveauContact->mail;
        $poste = $nouveauContact->poste;
        $societeId = $nouveauContact->societeId;
        $token = $nouveauContact->formContactToken;

        // On récupère la societe pour l'associer à l'adresse
        $societe = $societeRepository->find($societeId);

        // On créer une instance de Adresse
        $contact = new Contact();

        // On hydrate l'objet
        $contact->setNomContact($nom);
        $contact->setPrenomContact($prenom);
        $contact->setTelContact($telephone);
        $contact->setEmailContact($mail);
        $contact->setPosteContact($poste);
        $contact->setSociete($societe);

        // On créer une instance de la classe de formulaire que l'on associe à notre formulaire
        $contactForm = $this->createForm(ContactFormType::class, $contact);

        if ($request->getMethod() == 'POST') {

            // On prend les données du formulaire soumis, et les injecte dans $societe
            $contactForm->submit(array_merge(['nom_contact' => $nom, 'prenom_contact' => $prenom, 'tel_contact' => $telephone, 'email_contact' => $mail, 'poste_contact' => $poste, '_token' => $token], $request->request->all()), false);

            if ($contactForm->isSubmitted()) {
                if ($contactForm->isValid()) {
                    $resultat = 'success';

                    // Sauvegarde en Bdd
                    $entityManager->persist($contact);
                    $entityManager->flush();

                    // On ajoute un message flash
                    $this->addFlash("link", "Le contact a été ajouté");

                    // On retourne la réponse JSON
                    return new JsonResponse(['resultat' => $resultat]);
                } else {
                    // On retourne le tableau des erreurs de validation
                    $erreurs = $erreursServices->getErrorMessages($contactForm);
                }
            }
        }
        // On retourne la réponse JSON
        return new JsonResponse(['resultat' => $resultat, 'erreur' => $erreurs]);
    }

    /**
     * Supprime une société en fonction de son id
     * @Route("/societe/supprimer/{id}", name="societe_supprimer", requirements={"id"="\d+"})
     */
    public function societeSupprimer(
        $id,
        EntityManagerInterface $entityManager,
        SocieteRepository $societeRepository,
        LogoServices $logoServices
    ): Response {

        // On récupère la société
        $societe = $societeRepository->findAllInformationsBySociety($id);

        // On vérifie si un logo existe déjà pour le supprimer
        if ($societe->getLogo()) {
            $logoServices->deletePhoto($societe->getLogo());
        }

        // On supprime la société et on enregistre en bdd
        $entityManager->remove($societe);
        $entityManager->flush();

        // On ajoute un message flash
        $this->addFlash("link", "La société a été supprimée");

        // On redirige vers societe_liste
        return $this->redirectToRoute('societe_liste');
    }

    /**
     * Supprime une adresse secondaire d'une société en fonction de son id
     * @Route("/societe/modifier/adresse/supprimer/{idAdresse}", name="adresse_supprimer", requirements={"id"="\d+"})
     */
    public function adresseSupprimer(
        $idAdresse,
        EntityManagerInterface $entityManager,
        AdresseRepository $adresseRepository
    ): Response {

        // On récupère l'adresse
        $adresse = $adresseRepository->find($idAdresse);

        // On supprime l'adresse et on enregistre en bdd
        $entityManager->remove($adresse);
        $entityManager->flush();

        // On ajoute un message flash
        $this->addFlash("link", "L'adresse a été supprimée");

        // On redirige vers societe_liste
        return $this->redirectToRoute('societe_liste');
    }

    /**
     * Supprime un contact secondaire d'une société en fonction de son id
     * @Route("/societe/modifier/contact/supprimer/{idContact}", name="contact_supprimer", requirements={"id"="\d+"})
     */
    public function contactSupprimer(
        $idContact,
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository
    ): Response {

        // On récupère le contact
        $contact = $contactRepository->find($idContact);

        // On supprime le contact et on enregistre en bdd
        $entityManager->remove($contact);
        $entityManager->flush();

        // On ajoute un message flash
        $this->addFlash("link", "Le contact a été supprimée");

        // On redirige vers societe_liste
        return $this->redirectToRoute('societe_liste');
    }
}
