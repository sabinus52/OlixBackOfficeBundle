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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ActivitySubscriber pour stocker le time de la dernière activité.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class ActivitySubscriber implements EventSubscriberInterface
{
    /**
     * Delai en minutes à partir duquel l'utilisateur est considéré comme non connecté.
     */
    protected int $delay;

    /**
     * Constructeur.
     *
     * @param array<mixed> $olixConfigParameter
     */
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly Security $security, array $olixConfigParameter)
    {
        $this->delay = $olixConfigParameter['security']['delay_activity'];
    }

    /**
     * Retourne la liste des évènements.
     *
     * @return array<mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * Evènement sur l'affichage d'une page.
     */
    public function onKernelController(ControllerEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        // ici on vérifie que la requête est une "MASTER_REQUEST" pour que les sous-requêtes soient ingorées (par exemple si on fait un render() dans notre template)
        if (HttpKernelInterface::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        // On vérifie qu'un token d'autentification est bien présent avant d'essayer manipuler l'utilisateur courant.
        if (null !== $user) {
            // On utilise un délai pendant lequel on considère que l'utilisateur est toujours actif et qu'il n'est pas nécessaire de refaire de mise à jour
            $delay = new \DateTime();
            $timeDelay = (int) strtotime(sprintf('%s minutes ago', $this->delay));
            $delay->setTimestamp($timeDelay);

            // On vérifie que l'utilisateur est bien du bon type pour ne pas appeler getLastActivity() sur un objet autre objet User
            if ($user instanceof User && $user->getLastActivity() < $delay) {
                $user->setOnline();
                $this->entityManager->flush();
            }
        }
    }
}
