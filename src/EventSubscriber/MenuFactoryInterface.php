<?php

namespace Olix\BackOfficeBundle\EventSubscriber;

use Olix\BackOfficeBundle\Event\SidebarMenuEvent;

/**
 * Interface du subscriber sur le menu de la barre latérale
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface MenuFactoryInterface
{
    /**
     * Construction du menu de l'application
     * Classe à créer et à hériter de MenuFactorySubscriber
     *
     * @param SidebarMenuEvent $event : Evènement du menu de la barre latérale
     */
    public function build(SidebarMenuEvent $event): void;
}
