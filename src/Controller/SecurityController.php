<?php

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Classe pour l'authentification des utilisateurs.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class SecurityController extends AbstractController
{
    /**
     * Connextion à l'interface.
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
            'error' => $error,
        ]);
    }

    /**
     * Page de déconnexion.
     *
     * @Route("/logout", name="olix_logout")
     */
    public function logout(): void
    {
        throw new LogicException('This method can be blank - '.'it will be intercepted by the logout key on your firewall.');
    }
}
