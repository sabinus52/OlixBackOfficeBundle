<?php

namespace Olix\BackOfficeBundle\Listener;

use Olix\BackOfficeBundle\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use DateTime;

/**
 * Listener sur la connexion de l'utilisateur
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class LoginListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * Constructeur
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * Evenement au moment de la connexion de l'utilisateur
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();

        // Mise à jour de la date de login
        /** @var User $user */
        $user->setLastLogin(new DateTime());

        // Persist the data to database.
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
