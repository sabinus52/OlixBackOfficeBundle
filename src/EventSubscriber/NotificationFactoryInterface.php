<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\EventSubscriber;

use Olix\BackOfficeBundle\Event\NotificationsEvent;

/**
 * Interface du subscriber sur les notifications de la barres de navigation.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface NotificationFactoryInterface
{
    /**
     * Construction des notifications
     * Classe à créer et à hériter de NotificationFactorySubscriber.
     *
     * @param NotificationsEvent $event : Évènement des notifications
     */
    public function build(NotificationsEvent $event): void;
}
