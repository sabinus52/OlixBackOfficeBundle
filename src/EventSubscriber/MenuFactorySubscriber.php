<?php

namespace Olix\BackOfficeBundle\EventSubscriber;

use Olix\BackOfficeBundle\Model\MenuItemModel;
use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Olix\BackOfficeBundle\Event\BreadcrumbEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Exception;

/**
 * Subscriber sur le menu de la barre latérale à hériter pour créer son propre menu
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
abstract class MenuFactorySubscriber implements EventSubscriberInterface, MenuFactoryInterface
{
    /**
     * Configuration des options du bundle
     *
     * @var array
     */
    private $parameters = [
        'menu_activ' => false,
    ];

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Security
     */
    private $security;


    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;

        // Get parameter "olix_back_office.security"
        if (! $parameterBag->has('olix_back_office')) {
            throw new Exception('Parameter "olix_back_office" not defined', 1);
        }
        $parameters = $parameterBag->get('olix_back_office');
        if (array_key_exists('security', $parameters)) {
            $this->parameters = $parameters['security'];
        }
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
            BreadcrumbEvent::class  => ['onBuildSidebar', 100],
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

        // Add menu manage of users
        if ($this->security->isGranted('ROLE_ADMIN') && $this->parameters['menu_activ'] == true) {
            $event->addItem(new MenuItemModel('security', [
                'label'         => 'Gestion des utilisateurs',
                'route'         => 'olix_users__list',
                'icon'          => 'fas fa-users',
            ]));
        }

        $this->activateByRoute($event->getRequest()->get('_route'), $event->getSidebarMenu());
    }


    /**
     * Correspondance de la route par récursivité pour activer le menu en cours
     *
     * @param string $route
     * @param array $items : MenuItemInterface[]
     */
    protected function activateByRoute(string $route, ?array $items): void
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                if ($item->getRoute() == $route) {
                    $item->setIsActive(true); // TODO inclusio de chemin __
                }
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() == $route) {
                $item->setIsActive(true);
            }
        }
    }
}
