<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\EventSubscriber;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * Class ActivitySubscriber pour stocker le time de la dernière activité.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class ActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * Delai en minutes à partir duquel l'utilisateur est considéré comme non connecté.
     *
     * @var int
     */
    protected $delay;

    /**
     * Constructeur.
     *
     * @param EntityManagerInterface $entityManager
     * @param Security               $security
     * @param array<mixed>           $parameters
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security, array $parameters)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->delay = $parameters['security']['delay_activity'];
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
     *
     * @param ControllerEvent $event
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
            $delay = new DateTime();
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
