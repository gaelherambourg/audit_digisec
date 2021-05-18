<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\Societe;
use App\Form\SocieteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SocieteController extends AbstractController
{
    /**
     * @Route("/societe", name="societe_ajouter")
     */
    public function societeAjouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On créé une instance de société, Adresse, et Contact
        $societe = new Societe();
        $adresse = new Adresse();
        $client = new Client();

        // Crée une instance de la classe de formulaire que l'on associe à notre formulaire
        $societeForm = $this->createForm(SocieteFormType::class, $societe);

        // On prend les données du formulaire soumis, et les injecte dans $societe
        $societeForm->handleRequest($request);

        // Si le formulaire est soumis
        if ($societeForm->isSubmitted() && $societeForm->isValid()) {

            // On récupère les données de l'adresse
            $adresse->setRue($societeForm->get('rue')->getData());
            $adresse->setCodePostal($societeForm->get('code_postal')->getData());
            $adresse->setVille($societeForm->get('ville')->getData());

            // On récupère les données du contact
            $client->setNomContact($societeForm->get('nom_contact')->getData());
            $client->setTelContact($societeForm->get('mail_contact')->getData());
            $client->setMailContact($societeForm->get('tel_contact')->getData());
            
            // On modifie les données vide de la société
            $societe->setDateCreation(new \DateTime());
            $societe->setAdresse($adresse);
            $societe->setContact($client);
            
            // Sauvegarde en Bdd
            $entityManager->persist($societe);
            $entityManager->flush();

            // On ajoute un message flash
            $this->addFlash("link", "L'entreprise a été ajoutée");
        }

        return $this->render('societe/societe_ajouter.html.twig', [
            "societeForm" => $societeForm->createView(),
        ]);
    }
   
    /**
     * @Route("/societe_liste", name="societe_liste")
     */
    public function listerSociete()
    {
        return $this->render('societe/societe_liste.html.twig', [
        ]);
    }     
}