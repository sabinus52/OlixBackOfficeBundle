<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Olix\BackOfficeBundle\Form\UserCreateType;
use Olix\BackOfficeBundle\Form\UserPasswordType;
use Olix\BackOfficeBundle\Model\User;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Classe de la gestion des utilisateurs.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class UserManager implements UserManagerInterface
{
    /**
     * Configuration du bundle de la branche "security".
     *
     * @var array<mixed>
     */
    protected $parameters = [
        'menu_activ' => false,
        'class' => [
            'user' => 'App\Entity\User',
            'form_user' => 'Olix\BackOfficeBundle\Form\UserEditType',
            'form_profile' => 'Olix\BackOfficeBundle\Form\UserProfileType',
        ],
    ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var UserPasswordHasherInterface
     */
    protected $passwordHasher;

    /**
     * @var User
     */
    protected $user;

    /**
     * Constructeur.
     *
     * @param ContainerInterface          $container
     * @param ParameterBagInterface       $parameterBag
     * @param ManagerRegistry             $doctrine
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        ContainerInterface $container,
        ParameterBagInterface $parameterBag,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->container = $container;
        $this->doctrine = $doctrine;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $doctrine->getManager(); // @phpstan-ignore-line

        // Get parameter olix_back_office.security
        if (!$parameterBag->has('olix_back_office')) {
            throw new Exception('Parameter "olix_back_office" not defined', 1);
        }
        /** @var array<mixed> $parameters */
        $parameters = $parameterBag->get('olix_back_office');
        if (array_key_exists('security', $parameters)) {
            $this->parameters = $parameters['security'];
        }
    }

    /**
     * Retourne la classe de l'utilisateur.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->parameters['class']['user'];
    }

    /**
     * Retourne l'utilisateur.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Crée un nouvel objet utilisateur.
     *
     * @return UserInterface
     */
    public function newUser(): UserInterface
    {
        /** @var User $class */
        $class = $this->getClass();
        $this->user = new $class();

        return $this->user;
    }

    /**
     * Affecte un utilisateur dans le manager.
     *
     * @param User $user
     *
     * @return UserManager
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Affecte un utilisateur dans le manager via son identifiant.
     *
     * @return UserInterface|null
     */
    public function setUserById(int $idf): ?UserInterface
    {
        /** @var User $this->user */
        $this->user = $this->doctrine->getRepository($this->getClass())->find($idf); // @phpstan-ignore-line

        return $this->user;
    }

    /**
     * Retourne tous les utilisateurs.
     *
     * @return UserInterface[]
     */
    public function findAll(): array
    {
        return $this->doctrine->getRepository($this->getClass())->findAll(); // @phpstan-ignore-line
    }

    /**
     * Ajoute un nouvel utilisateur en base.
     *
     * @param string $password
     */
    public function add(string $password): void
    {
        $this->user->setPassword($this->getHashedPassword($password));
        $this->update();
    }

    /**
     * Mets à jour les données de l'utilisateur.
     *
     * @param string $password
     */
    public function update(string $password = null): void
    {
        if ($password) {
            $this->user->setPassword($this->getHashedPassword($password));
        }
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    /**
     * Supprime un utilisateur en base.
     */
    public function remove(): void
    {
        $this->entityManager->remove($this->user);
        $this->entityManager->flush();
    }

    /**
     * Retourne un mot de passe haché.
     *
     * @param string $plaintextPassword
     *
     * @return string
     */
    protected function getHashedPassword(string $plaintextPassword): string
    {
        return $this->passwordHasher->hashPassword($this->user, $plaintextPassword);
    }

    /**
     * Crée le formulaire de création d'un utilisateur.
     *
     * @param array<mixed> $options
     *
     * @return FormInterface
     */
    public function createFormCreateUser(array $options = []): FormInterface
    {
        return $this->createForm(UserCreateType::class, $options);
    }

    /**
     * Crée le formulaire de modification d'un utilisateur.
     *
     * @param array<mixed> $options
     *
     * @return FormInterface
     */
    public function createFormEditUser(array $options = []): FormInterface
    {
        $class = $this->parameters['class']['form_user'];

        return $this->createForm($class, $options);
    }

    /**
     * Crée le formulaire de profile d'un utilisateur.
     *
     * @param array<mixed> $options
     *
     * @return FormInterface
     */
    public function createFormProfileUser(array $options = []): FormInterface
    {
        $class = $this->parameters['class']['form_profile'];

        return $this->createForm($class, $options);
    }

    /**
     * Crée le formulaire de changement de mot de passe d'un utilisateur.
     *
     * @param array<mixed> $options
     *
     * @return FormInterface
     */
    public function createFormChangePassword(array $options = []): FormInterface
    {
        return $this->createForm(UserPasswordType::class, $options);
    }

    /**
     * Création d'un formulaire spécifique.
     *
     * @param string       $type    : Nom de la classe fu formulaire
     * @param array<mixed> $options
     *
     * @return FormInterface
     */
    protected function createForm(string $type, array $options = []): FormInterface
    {
        $options = $options + ['data_class' => $this->getClass()];

        return $this->container->get('form.factory')->create($type, $this->user, $options);
    }
}
