# Implémentation de la connexion utilisateur

## Surcharge de l'entité User

Il faut d'abord créer et surcharger l'entité `User` :

~~~ php
# src/Entity/User.php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Olix\BackOfficeBundle\Model\User as BaseUser;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends BaseUser
{
    #[ORM\Column(length: 250, nullable: true)]
    protected ?string $avatar = null;
    
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
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
            user_checker: Olix\BackOfficeBundle\Security\UserChecker

            form_login:
                # "login" is the name of the route created previously
                login_path: olix_login
                check_path: olix_login
                enable_csrf: true
            logout:
                path: olix_logout
### END
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
    #[Route('/login', name: 'olix_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('base-login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'olix_logout')]
    public function logout(AuthenticationUtils $authenticationUtils)
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
~~~


## Activation du menu de gestion des utilisateurs

Pour l'activer, il faut positionner ce paramètre à *true* dans le fichier de configuration **olix_bo.yaml**:
~~~ yaml
    security:
        menu_activ: true
~~~


## Controle d'accès

Pour interdire entièrement l'accès à l'application, il faut configurer de la manière suivante dans la configuration *config/security.yaml*

~~~ yaml
access_control:
    - { path: ^/login, roles: PUBLIC_ACCESS }
    - { path: ^/, roles: ROLE_USER }
~~~

## Création d'un utilisateur

A mettre dans un controleur
~~~ php
use App\Entity\User;
use Olix\BackOfficeBundle\Security\UserManager;

class DefaultController extends AbstractController
{
    #[Route('/adduser', name: 'adduser')]
    public function index(UserManager $manager): Response
    {

        /** @var User $user */
        $user = $manager->newUser();

        $user->setUsername('admin')
            ->setName('admin')
            ->setRoles(['ROLE_ADMIN']);
        $manager->setUser($user);
        $manager->add('toto');

        return new Response('OK');
    }
}
~~~