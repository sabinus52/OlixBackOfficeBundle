<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Twig;

use Olix\BackOfficeBundle\Event\BreadcrumbEvent;
use Olix\BackOfficeBundle\Event\NotificationsEvent;
use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Olix\BackOfficeBundle\Model\MenuItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Runtime des "functions" personnalisés TWIG pour les évènements.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @see        https://symfony.com/doc/current/templating/twig_extension.html#creating-lazy-loaded-twig-extensions
 */
class EventsRuntime implements RuntimeExtensionInterface
{
    /**
     * Constructor.
     */
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Retourne le menu de la barre latérale.
     *
     * @return MenuItemInterface[]
     */
    public function getSidebarMenu(Request $request, string $forceMenuActiv): ?array
    {
        if (!$this->eventDispatcher->hasListeners(SidebarMenuEvent::class)) {
            return null;
        }

        /** @var SidebarMenuEvent $event */
        $event = $this->eventDispatcher->dispatch(new SidebarMenuEvent($request, $forceMenuActiv));

        return $event->getSidebarMenu();
    }

    /**
     * Retourne le fil d'ariane.
     *
     * @return MenuItemInterface[]
     */
    public function getBreadcrumb(Request $request, string $forceMenuActiv): ?array
    {
        if (!$this->eventDispatcher->hasListeners(BreadcrumbEvent::class)) {
            return null;
        }

        /** @var BreadcrumbEvent $event */
        $event = $this->eventDispatcher->dispatch(new BreadcrumbEvent($request, $forceMenuActiv));

        /** @var MenuItemInterface $active */
        $active = $event->getActive();
        $list = [];
        if (null !== $active) {
            $list[] = $active;
            while (null !== ($item = $active->getActiveChild())) {
                $list[] = $item;
                $active = $item;
            }
        }

        return $list;
    }

    /**
     * Retourne la liste des notifications de la barre de navigation.
     *
     * @return NotificationsEvent
     */
    public function getNotifications(): ?NotificationsEvent
    {
        if (!$this->eventDispatcher->hasListeners(NotificationsEvent::class)) {
            return null;
        }

        /** @var NotificationsEvent */
        return $this->eventDispatcher->dispatch(new NotificationsEvent());
    }
}
