<?php
/**
 * Classe abstraite de la gestion des utilisateurs
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Model;

use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;


abstract class UserManager implements UserManagerInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var UserPasswordHasherInterface
     */
    protected $passwordHasher;

    /**
     * @var UserInterface
     */
    protected $user;


    /**
     * Constructeur
     * 
     * @param ContainerInterface $container
     * @param EntityManagerInterface $doctrine
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $this->container= $container;
        $this->entityManager = $doctrine;
        $this->passwordHasher = $passwordHasher;
    }


    /**
     * @param UserInterface $user
     * @return UserManagerInterface
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }


    /**
     * Création d'un formulaire spécifique
     * 
     * @param string $type : Nom de la classe fu formulaire
     * @param array $options
     * @return FormInterface
     */
    public function createForm(string $type, array $options = []): FormInterface
    {
        $options = $options + [ 'data_class' => $this->getClass() ];
        return $this->container->get('form.factory')->create($type, $this->user, $options);
    }


    /**
     * Mise des données de l'utilisateur à partir du formulaire
     * 
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function updateUser(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }


    /**
     * Mise du mot de passe de l'utilisateur à partir du formulaire
     * 
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function updatePassword(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }
    
}