<?php

namespace Olix\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use LogicException;

/**
 * Classe pour l'authentification des utilisateurs
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class SecurityController extends AbstractController
{
    /**
     * Connextion à l'interface
     *
     * @Route("/login", name="olix_login")
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
    public function logout(): void
    {
        throw new LogicException('This method can be blank - '
            . 'it will be intercepted by the logout key on your firewall.');
    }
}
