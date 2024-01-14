<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Helper\Gravatar;
use Olix\BackOfficeBundle\Model\User;
use Olix\BackOfficeBundle\Security\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur des pages du profil de l'utilisateur.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
#[IsGranted('ROLE_USER', message: 'You are not allowed to access the user profile.')]
class ProfileController extends AbstractController
{
    /**
     * Page de profile.
     */
    #[Route(path: '/profile', name: 'olix_profile')]
    public function profile(Request $request, UserManager $manager): Response
    {
        // Utilisation de la classe UserManager
        /** @var User $user */
        $user = $this->getUser();
        $manager->setUser($user);

        // Création des formulaires
        $form1 = $manager->createFormProfileUser();
        $form2 = $manager->createFormProfilePassword();

        // Validation du formulaire de profile de l'utilisateur
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            // Update datas of this user
            $manager->setUser($form1->getData())->update();
            $this->addFlash('success', 'La modification des informations a bien été prise en compte');

            return $this->redirectToRoute('olix_profile');
        }

        // Validation de formulaire de modification du mot de passe
        $form2->handleRequest($request);
        if ($form2->isSubmitted()) {
            $isError = false;

            if (!$form2->isValid()) {
                $form2->addError(new FormError('Nouveau mot de passe incorrect'));
                $isError = true;
            }

            if (!$manager->isPasswordValid($form2->get('oldPassword')->getData())) {
                $form2->addError(new FormError('Ancien mot de passe incorrect'));
                $isError = true;
            }

            if (!$isError) {
                // Change password for this user
                $manager->update($form2->get('password')->getData());
                $this->addFlash('success', 'La modification du mot de passe a bien été prise en compte');

                return $this->redirectToRoute('olix_profile');
            }
        }

        // Rendu de la page
        return $this->render('@OlixBackOffice/Security/profile.html.twig', [
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
        ]);
    }

    /**
     * Affichage des avatars.
     */
    #[Route(path: '/profile/avatar', name: 'olix_profile_avatar')]
    public function choiceAvatar(): Response
    {
        // Chargement des éléments
        $finder = new Finder();
        $gravatar = new Gravatar();
        /** @var User $user */
        $user = $this->getUser();
        $result = [];

        // Charge la liste des images avatar
        $finder->files()->in(__DIR__.'/../../public/images/avatar')->name('*.png');
        foreach ($finder as $files) {
            $result[$files->getRelativePath()][] = $files->getRelativePathname();
        }

        return $this->render('@OlixBackOffice/Security/_avatar.html.twig', [
            'avatars' => $result,
            'gravatar' => $gravatar->get($user->getEmail()),
        ]);
    }

    /**
     * Change l'avatar de l'utilisateur.
     */
    #[Route(path: '/profile/avatar/change', name: 'olix_profile_avatar_change')]
    public function changeAvatar(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var string $avatar */
        $avatar = $request->query->get('avatar');

        // Modifie l'avatar
        $user->setAvatar($avatar);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('olix_profile');
    }
}
