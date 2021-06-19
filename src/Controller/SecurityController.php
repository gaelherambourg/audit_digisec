<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Form\ResetPassType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
     /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //si on essaye d'accéder à la page login déjà connecté
        if ($this->getUser()) {
            $this->addFlash("link", "Vous êtes déjà connecté");
            return $this->redirectToRoute('audit_liste');
        }

        // obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            // On ajoute un message flash
            $this->addFlash("danger", "Merci de vérifier votre identifiant et/ou mot de passe");
        }
        // dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // On retourne la page Twig de l'accueil
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.');
    }

    /**
    * @Route("/login/oubli-pass", name="app_forgotten_password")
    */
    public function oubliPass(Request $request,
                              UtilisateurRepository $utilisateur,
                              \Swift_Mailer $mailer,
                              TokenGeneratorInterface $tokenGenerator): Response
        {
            // On initialise le formulaire
            $form = $this->createForm(ResetPassType::class);

            // On traite le formulaire
            $form->handleRequest($request);

            // Si le formulaire est valide
            if ($form->isSubmitted() && $form->isValid()) {
                // On récupère les données
                $donnees = $form->getData();

                // On cherche un utilisateur ayant cet e-mail
                $utilisateur = $utilisateur->findOneByEmail($donnees['email']);

                // Si l'utilisateur n'existe pas
                if (!$utilisateur) {
                    // On envoie une alerte disant que l'adresse e-mail est inconnue
                    $this->addFlash('danger', 'Cette adresse email n\'existe pas');
                    
                    // On retourne sur la page de connexion
                    return $this->redirectToRoute('app_login');
                }

                // On génère un token
                $token = $tokenGenerator->generateToken();

                // On essaie d'écrire le token en base de données
                try{
                    $utilisateur->setResetToken($token);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'Une erreur est survenue' . $e->getMessage());
                    return $this->redirectToRoute('app_login');
                }

                // On génère l'URL de réinitialisation de mot de passe
                $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                // On génère l'e-mail
                $message = (new \Swift_Message('Mot de passe oublié'))
                    ->setFrom('entreprise.digisec@gmail.fr')
                    ->setTo($utilisateur->getEmail())
                    ->setBody(
                        "<p>Bonjour,</p><p>Une demande de réinitialisation de mot de passe a été effectuée pour le site Digisec. Veuillez cliquer sur le lien suivant : " . $url . '</p>',
                        'text/html'
                    )
                ;

                // On envoie l'e-mail
                $mailer->send($message);

                // On crée le message flash de confirmation
                $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

                // On redirige vers la page de login
                return $this->redirectToRoute('app_login');
            }

            // On envoie le formulaire à la vue
            return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
        }

        /**
         * @Route("login/reset_pass/{token}", name="app_reset_password")
         */
        public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
        {
            // On cherche un utilisateur avec le token donné
            $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['reset_token' => $token]);

            // Si l'utilisateur n'existe pas
            if ($utilisateur === null) {
                // On affiche une erreur
                $this->addFlash('danger', 'Vous avez déjà modifié votre mot de passe');
                return $this->redirectToRoute('app_login');
            }

            // Si le formulaire est envoyé en méthode post
            if ($request->isMethod('POST')) {
                // On supprime le token
                $utilisateur->setResetToken(null);

                // On chiffre le mot de passe
                $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $request->request->get('password')));

                // On stocke
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                // On crée le message flash
                $this->addFlash('message', 'Mot de passe modifié avec succès');

                // On redirige vers la page de connexion
                return $this->redirectToRoute('app_login');
            }else {
                // Si on n'a pas reçu les données, on affiche le formulaire
                return $this->render('security/reset_password.html.twig', ['token' => $token]);
            }

        }
}
