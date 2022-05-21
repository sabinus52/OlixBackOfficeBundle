<?php

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\EventSubscriber;

use Olix\BackOfficeBundle\Event\SidebarMenuEvent;

/**
 * Interface du subscriber sur le menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface MenuFactoryInterface
{
    /**
     * Construction du menu de l'application
     * Classe à créer et à hériter de MenuFactorySubscriber.
     *
     * @param SidebarMenuEvent $event : Evènement du menu de la barre latérale
     */
    public function build(SidebarMenuEvent $event): void;
}
