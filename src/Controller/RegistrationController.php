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
        $utilisateur = new Utilisateur();
        $utilisateur->setAdmin(true);
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $utilisateur->setPassword(
                $passwordEncoder->encodePassword(
                    $utilisateur,
                    $form->get('plainPassword')->getData()
                )
            );

            // On génère un token et on l'enregistre
            $utilisateur->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouvel utilisteur '.$utilisateur->getUsername().' a bien été créé.');
            return $this->redirectToRoute('app_login');

            // do anything else you need here, like send an email

            // On crée le message
            $message = (new \Swift_Message('Nouveau compte'))
            // On attribue l'expéditeur
            ->setFrom('gael@digisec.fr')
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
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // On génère un message
            $this->addFlash('message', 'Utilisateur activé avec succès');

            // On retourne à l'accueil
            return $this->redirectToRoute('audit_liste');
        }
}
