<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, 
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             AppAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        // On créé une instance d'utilisateur
        $utilisateur = new Utilisateur();
        $utilisateur->setAdmin(true);

        // Crée une instance de la classe de formulaire que l'on associe à notre formulaire
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);

        // On prend les données du formulaire soumis, et les injecte dans $utilisateur
        $form->handleRequest($request);

        // Si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // on encode le mot de passe
            $utilisateur->setPassword(
                $passwordEncoder->encodePassword(
                    $utilisateur,
                    $form->get('plainPassword')->getData()
                )
            );

            // On génère un token et on l'enregistre
            $utilisateur->setActivationToken(md5(uniqid()));

            // Sauvegarde en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            
            // On ajoute un message flash
            $this->addFlash('success', 'Le nouvel utilisteur '.$utilisateur->getUsername().' a bien été créé.');
            return $this->redirectToRoute('app_login');

            // On crée le message
            $message = (new \Swift_Message('Nouveau compte'))

            // On attribue l'expéditeur
            ->setFrom('entreprise.digisec@gmail.fr')

            // On attribue le destinataire
            ->setTo($utilisateur->getEmail())

            // On crée le texte avec la vue
            ->setBody(
                $this->renderView(
                    'emails/activation.html.twig', ['token' => $utilisateur->getActivationToken()]
                ),
                'text/html'
                )
            ;
            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $utilisateur,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        // On retourne la page Twig avec le formulaire
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
 
    }

        /**
         * @Route("/activation/{token}", name="activation")
         */
        public function activation($token, UtilisateurRepository $utilisateur)
        {
            // On recherche si un utilisateur avec ce token existe dans la base de données
            $utilisateur = $utilisateur->findOneBy(['activation_token' => $token]);

            // Si aucun utilisateur n'est associé à ce token
            if(!$utilisateur){
                // On renvoie une erreur 404
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }

            // On supprime le token
            $utilisateur->setActivationToken(null);
            $entityManager = $this->getDoctrine()->getManager();

            // Sauvegarde en base de données
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // On génère un message
            $this->addFlash('message', 'Utilisateur activé avec succès');

            // On retourne la page Twig de l'accueil
            return $this->redirectToRoute('audit_liste');
        }
}
