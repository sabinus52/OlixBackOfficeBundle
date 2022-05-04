<?php
/**
 * Subscriber sur le menu de la barre latérale à hériter pour créer son propre menu
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\EventSubscriber;

use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


abstract class MenuFactorySubscriber implements EventSubscriberInterface, MenuFactoryInterface
{

    protected $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * Retourne la liste des évènements
     * 
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SidebarMenuEvent::class => ['onBuildSidebar', 100],
        ];
    }


    /**
     * Generate the main menu.
     *
     * @param SidebarMenuEvent $event
     */
    public function onBuildSidebar(SidebarMenuEvent $event)
    {
        $this->build($event);

        $this->activateByRoute($event->getRequest()->get('_route'), $event->getSidebarMenu());
    }


    /**
     * Correspondance de la route par récursivité pour activer le menu en cours
     * 
     * @param string $route
     * @param MenuItemInterface[] $items
     */
    protected function activateByRoute(string $route, ?array $items): void
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() == $route) {
                $item->setIsActive(true);
            }
        }
    }

}
