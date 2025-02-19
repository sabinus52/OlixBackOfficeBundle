<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Event\NotificationsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber sur les notifications de la barre de navigation à hériter pour créer ses propres notifications.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
abstract class NotificationFactorySubscriber implements EventSubscriberInterface, NotificationFactoryInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Retourne la liste des évènements.
     *
     * @return array<mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationsEvent::class => ['onBuildNotification', 100],
        ];
    }

    /**
     * Generate les notifications de la barre de navigation.
     */
    public function onBuildNotification(NotificationsEvent $event): void
    {
        $this->build($event);
    }
}
