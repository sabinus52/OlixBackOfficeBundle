<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Listener sur la connexion de l'utilisateur.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class LoginListener
{
    /**
     * Constructeur.
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Evenement au moment de la connexion de l'utilisateur.
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();

        // Mise à jour de la date de login
        /** @var User $user */
        $user->setLastLogin(new \DateTime());

        // Persist the data to database.
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
