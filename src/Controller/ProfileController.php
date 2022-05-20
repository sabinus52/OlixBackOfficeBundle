<?php
/**
 * Contrôleur des pages du profil de l'utilisateur
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */
namespace Olix\BackOfficeBundle\Controller;

use Olix\BackOfficeBundle\Helper\Gravatar;
use Olix\BackOfficeBundle\Security\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ProfileController extends AbstractController
{

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
        $form1 = $manager->createFormProfileUser();
        $form2 = $manager->createFormChangePassword();

        // Validation du formulaire de profile de l'utilisateur
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {

            // Update datas of this user
            $manager->setUser($form1->getData())->update();

            return $this->redirectToRoute('olix_profile');
        }

        // Validation de formulaire de modification du mot de passe
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {

            // Change password for this user
            $manager->changePassword($form2->get('password')->getData());

            return $this->redirectToRoute('olix_profile');
        }

        // Rendu de la page
        return $this->renderForm('@OlixBackOffice/Security/profile.html.twig', [
            'form1' => $form1,
            'form2' => $form2,
        ]);      
    }


    /**
     * Affichage des avatars
     * 
     * @Route("/profile/avatar", name="olix_profile_avatar")
     * @Security("is_granted('ROLE_USER')")
     */
    public function choiceAvatar(): Response
    {
        // Chargement des éléments
        $finder = new Finder();
        $gravatar = new Gravatar();
        $user = $this->getUser();
        $result = array();

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
     * Change l'avatar de l'utilisateur
     * 
     * @Route("/profile/avatar/change", name="olix_profile_avatar_change")
     * @Security("is_granted('ROLE_USER')")
     */
    public function changeAvatar(Request $request, ManagerRegistry $doctrine): Response
    {
        // Chargement des éléments
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        // Modifie l'avatar
        $user->setAvatar( $request->query->get('avatar') );
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('olix_profile');
    }

}