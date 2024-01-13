<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Controller;

use Olix\BackOfficeBundle\Security\UserDatatable;
use Olix\BackOfficeBundle\Security\UserManager;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller des pages de la gestion des utilisateurs.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
#[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
class ManagerController extends AbstractController
{
    /**
     * @var array<mixed>
     */
    private $parameters = [
        'menu_activ' => false,
        'delay_activity' => 5,
    ];

    /**
     * Constructeur.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        // Get parameter "olix_back_office.security"
        if (!$parameterBag->has('olix_back_office')) {
            throw new \Exception('Parameter "olix_back_office" not defined', 1);
        }

        /** @var array<mixed> $parameters */
        $parameters = $parameterBag->get('olix_back_office');
        if (array_key_exists('security', $parameters)) {
            $this->parameters = $parameters['security'];
        }
    }

    /**
     * Affichage de la liste des utiliseurs.
     */
    #[Route(path: '/security/users', name: 'olix_users__list')]
    public function listUsers(UserManager $manager, Request $request, DataTableFactory $factory): Response
    {
        $this->checkAccess();

        $datatable = $factory->createFromType(UserDatatable::class, [
            'entity' => $manager->getClass(),
            'delay' => $this->parameters['delay_activity'],
        ], [
            'searching' => true,
        ])
            ->handleRequest($request)
        ;

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('@OlixBackOffice/Security/users-list.html.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * Création d'un nouvel utilisateur.
     */
    #[Route(path: '/security/users/create', name: 'olix_users__create')]
    public function createUser(UserManager $manager, Request $request): Response
    {
        $this->checkAccess();

        // Initialize new user
        $manager->newUser();

        // Create form and upgrade on validation form
        $form = $manager->createFormCreateUser();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Add this new user
            $manager->setUser($form->getData());
            $manager->add($form->get('password')->getData());

            $this->addFlash('success', 'La création de l\'utilisateur <b>'.$manager->getUser()->getUserIdentifier().'</b> a bien été prise en compte');

            return $this->redirectToRoute('olix_users__edit', ['id' => $manager->getUser()->getId()]);
        }

        return $this->render('@OlixBackOffice/Security/users-create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modification de l'utilisateur.
     */
    #[Route(path: '/security/users/edit/{id}', name: 'olix_users__edit')]
    public function editUser(UserManager $manager, Request $request): Response
    {
        $this->checkAccess();
        $idUser = (int) $request->get('id');

        // Get user from request
        $user = $manager->setUserById($idUser);
        if (!$user instanceof UserInterface) {
            $this->redirectToRoute('olix_users__list');
        }

        // Create form and upgrade on validation form
        $form = $manager->createFormEditUser();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Update datas of this user
            $manager->setUser($form->getData())->update();

            $this->addFlash('success', 'La modification de l\'utilisateur <b>'.$user->getUserIdentifier().'</b> a bien été prise en compte');

            return $this->redirectToRoute('olix_users__list');
        }

        return $this->render('@OlixBackOffice/Security/users-edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Change le mot de passe de l'utilisateur.
     */
    #[Route(path: '/security/users/password/{id}', name: 'olix_users__password')]
    public function changePassword(UserManager $manager, Request $request): Response
    {
        $this->checkAccess();
        $idUser = (int) $request->get('id');

        // Get user from request
        $user = $manager->setUserById($idUser);
        if (!$user instanceof UserInterface) {
            $this->redirectToRoute('olix_users__list');
        }

        // Create form and upgrade on validation form
        $form = $manager->createFormChangePassword();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Change password for this user
            $manager->update($form->get('password')->getData());

            $this->addFlash('success', 'La modification du mot de passe de l\'utilisateur <b>'.$user->getUserIdentifier().'</b> a bien été prise en compte');

            return $this->redirectToRoute('olix_users__list');
        }

        return $this->render('@OlixBackOffice/Security/users-password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Suppression d'un utilisateur.
     */
    #[Route(path: '/security/users/remove/{id}', name: 'olix_users__remove')]
    public function removeUser(UserManager $manager, Request $request): Response
    {
        $this->checkAccess();

        // Get user from request
        $idUser = (int) $request->get('id');
        $user = $manager->setUserById($idUser);
        if (!$user instanceof UserInterface) {
            $this->redirectToRoute('olix_users__list');
        }

        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Remove this user
            $manager->remove();

            $this->addFlash('success', 'La suppression de l\'utilisateur <b>'.$user->getUserIdentifier().'</b> a bien été prise en compte');

            return new Response('OK');
        }

        return $this->render('@OlixBackOffice/Include/modal-content-delete.html.twig', [
            'form' => $form,
            'element' => sprintf('l\'utilisateur <strong>%s</strong>', $user->getUserIdentifier()),
        ]);
    }

    /**
     * Vérifie si on autorise en fonction du paramètre "security.menu_activ".
     */
    protected function checkAccess(): bool
    {
        if (!isset($this->parameters['menu_activ']) || true !== $this->parameters['menu_activ']) {
            throw new \Exception('Asses denied', 1); // FIXME
        }

        return true;
    }
}
