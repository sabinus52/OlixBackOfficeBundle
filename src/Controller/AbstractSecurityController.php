<?php
/**
 * Classe abstraite pour exemple de controler de la gestion des utilisateurs
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


abstract class AbstractSecurityController extends AbstractController
{

    /**
     * Connextion à l'interface
     * 
     * @Route("/login", name="olixbo_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@OlixBackOffice/Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    /**
     * Page de déconnexion
     * 
     * @Route("/logout", name="olix_logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils)
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * Page de profile
     * 
     * @Route("/profile", name="olix_profile")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profile(Request $request, UserManager $manager): Response
    {
        // Utilisation de la classe UserManager
        $user = $this->getUser();
        $manager->setUser($user);

        // Création des formulaires
        $form1 = $manager->createForm(UserType::class);
        $form2 = $manager->createForm(UserPasswordType::class);

        // Validation du formulaire de modification de l'utilisateur
        if ( $manager->updateUser($form1, $request) ) {
            return $this->redirectToRoute('olix_profile');
        }

        // Validation de formulaire de modification du mot de passe
        if ( $manager->updatePassword($form2, $request) ) {
            return $this->redirectToRoute('olix_profile');
        }

        // Rendu de la page
        return $this->renderForm('@OlixBackOffice/Security/profile.html.twig', [
            'form1' => $form1,
            'form2' => $form2,
        ]);      
    }

}