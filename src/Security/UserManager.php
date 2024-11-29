<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Form\UserCreateType;
use Olix\BackOfficeBundle\Form\UserEditPassType;
use Olix\BackOfficeBundle\Form\UserPasswordType;
use Olix\BackOfficeBundle\Helper\ParameterOlix;
use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Classe de la gestion des utilisateurs.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class UserManager implements UserManagerInterface
{
    protected User $user;

    /**
     * Constructeur.
     */
    public function __construct(
        protected readonly FormFactoryInterface $formFactory,
        protected readonly ParameterOlix $parameterOlix,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * Retourne la classe de l'utilisateur.
     */
    public function getClass(): string
    {
        return (string) $this->parameterOlix->getValue('security.class.user'); // @phpstan-ignore cast.string
    }

    /**
     * Retourne l'utilisateur.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Crée un nouvel objet utilisateur.
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
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Affecte un utilisateur dans le manager via son identifiant.
     */
    public function setUserById(int $idf): ?UserInterface
    {
        /** @var User $this->user */
        $this->user = $this->entityManager->getRepository($this->getClass())->find($idf); // @phpstan-ignore-line

        return $this->user;
    }

    /**
     * Retourne tous les utilisateurs.
     *
     * @return UserInterface[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository($this->getClass())->findAll(); // @phpstan-ignore-line
    }

    /**
     * Ajoute un nouvel utilisateur en base.
     */
    public function add(string $password): void
    {
        $this->user->setPassword($this->getHashedPassword($password));
        $this->update();
    }

    /**
     * Mets à jour les données de l'utilisateur.
     */
    public function update(?string $password = null): void
    {
        if (null !== $password) {
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
     */
    protected function getHashedPassword(string $plaintextPassword): string
    {
        return $this->passwordHasher->hashPassword($this->user, $plaintextPassword);
    }

    /**
     * Vérifie si le mot courant est le bon.
     */
    public function isPasswordValid(string $password): bool
    {
        return $this->passwordHasher->isPasswordValid($this->user, $password);
    }

    /**
     * Crée le formulaire de création d'un utilisateur.
     *
     * @param array<mixed> $options
     */
    public function createFormCreateUser(array $options = []): FormInterface
    {
        return $this->createForm(UserCreateType::class, $options);
    }

    /**
     * Crée le formulaire de modification d'un utilisateur.
     *
     * @param array<mixed> $options
     */
    public function createFormEditUser(array $options = []): FormInterface
    {
        $class = (string) $this->parameterOlix->getValue('security.class.form_user'); // @phpstan-ignore cast.string

        return $this->createForm($class, $options);
    }

    /**
     * Crée le formulaire de profile d'un utilisateur.
     *
     * @param array<mixed> $options
     */
    public function createFormProfileUser(array $options = []): FormInterface
    {
        $class = (string) $this->parameterOlix->getValue('security.class.form_profile'); // @phpstan-ignore cast.string

        return $this->createForm($class, $options);
    }

    /**
     * Crée le formulaire de changement de mot de passe depuis le profile utilisateur.
     *
     * @param array<mixed> $options
     */
    public function createFormProfilePassword(array $options = []): FormInterface
    {
        return $this->createForm(UserPasswordType::class, $options);
    }

    /**
     * Crée le formulaire de changement de mot de passe d'un utilisateur.
     *
     * @param array<mixed> $options
     */
    public function createFormChangePassword(array $options = []): FormInterface
    {
        return $this->createForm(UserEditPassType::class, $options);
    }

    /**
     * Création d'un formulaire spécifique.
     *
     * @param string       $type    : Nom de la classe fu formulaire
     * @param array<mixed> $options
     */
    protected function createForm(string $type, array $options = []): FormInterface
    {
        $options += ['data_class' => $this->getClass()];

        return $this->formFactory->create($type, $this->user, $options);
    }
}
