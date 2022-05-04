<?php
/**
 * Runtime des "functions" personnalisés TWIG pour les évènements
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 * @see https://symfony.com/doc/current/templating/twig_extension.html#creating-lazy-loaded-twig-extensions
 */

namespace Olix\BackOfficeBundle\Twig;

use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\RuntimeExtensionInterface;


class EventsRuntime implements RuntimeExtensionInterface
{

    /**
     * Dispatcher (listener)
     * 
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }


    /**
     * Retourne le menu de la barre latérale
     * 
     * @return array
     */
    public function getSidebarMenu(Request $request): ?array
    {
        if ( !$this->eventDispatcher->hasListeners(SidebarMenuEvent::class) ) {
            return null;
        }

        /** @var SidebarMenuEvent $event */
        $event = $this->eventDispatcher->dispatch(new SidebarMenuEvent($request));

        return $event->getSidebarMenu();
    }

}