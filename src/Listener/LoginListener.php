<?php
/**
 * Listener sur la connexion de l'utilisateur
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class LoginListener
{

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * Constructeur
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $user->setLastLogin(new \DateTime());

        // Persist the data to database.
        $this->em->persist($user);
        $this->em->flush();
    }

}