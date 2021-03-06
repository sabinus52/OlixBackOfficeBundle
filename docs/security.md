# Implémentation de la connexion utilisateur

## Surcharge de l'entité User

Il faut d'abord créer et surcharger l'entité `User` :

~~~ php
# src/Entity/User.php

namespace App\Entity;

use Olix\BackOfficeBundle\Entity\User as BaseUser;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $avatar;
    
    public function getAvatar(): string
    {
        return (string) $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
~~~

~~~ php
# src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
~~~


## Configuration du fichier de paramètre

~~~ yml
security:
    enable_authenticator_manager: true
    # https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords
    password_hashers:
        Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
### BEGIN add
        App\Entity\User:
            algorithm: auto
### END

    # https://symfony.com/doc/current/security.html#loading-the-user-the-user-provider
    providers:
        # used to reload user from session & other features (e.g. switch_user)
### BEGIN add
        app_user_provider:
            entity:
                class: App\Entity\User
                property: username
### END
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            lazy: true
### BEGIN add
            provider: app_user_provider

            form_login:
                # "login" is the name of the route created previously
                login_path: login
                check_path: login
                enable_csrf: true
            logout:
                path: logout
### BEGIN add
~~~


## Surcharge du template

Il faut créer **OBLIGATOIREMENT** le fichier `base-login.html.twig` qui est utilisé par la page de login

~~~ twig
{# templates/base-login.html.twig #}

{% extends '@OlixBackOffice/Security/layout.html.twig' %}

{% block login_logo %}<b>Symfony 5</b> Demo{% endblock %}

{% block login_message %}Connexion à l'application{% endblock %}
~~~


## Surcharge du controller (facultatif)

~~~ php
# src/Controller/SecurityController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils)
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
~~~