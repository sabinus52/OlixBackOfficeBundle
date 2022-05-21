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
use Exception;
use Olix\BackOfficeBundle\Event\BreadcrumbEvent;
use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Olix\BackOfficeBundle\Model\MenuItemInterface;
use Olix\BackOfficeBundle\Model\MenuItemModel;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Subscriber sur le menu de la barre latérale à hériter pour créer son propre menu.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
abstract class MenuFactorySubscriber implements EventSubscriberInterface, MenuFactoryInterface
{
    /**
     * Configuration des options du bundle.
     *
     * @var array<mixed>
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
        if (!$parameterBag->has('olix_back_office')) {
            throw new Exception('Parameter "olix_back_office" not defined', 1);
        }
        /** @var array<mixed> $parameters */
        $parameters = $parameterBag->get('olix_back_office');
        if (array_key_exists('security', $parameters)) {
            $this->parameters = $parameters['security'];
        }
    }

    /**
     * Retourne la liste des évènements.
     *
     * @return array<mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SidebarMenuEvent::class => ['onBuildSidebar', 100],
            BreadcrumbEvent::class => ['onBuildSidebar', 100],
        ];
    }

    /**
     * Generate the main menu.
     *
     * @param SidebarMenuEvent $event
     */
    public function onBuildSidebar(SidebarMenuEvent $event): void
    {
        $this->build($event);

        // Add menu manage of users
        if ($this->security->isGranted('ROLE_ADMIN') && true === $this->parameters['menu_activ']) {
            $event->addItem(new MenuItemModel('security', [
                'label' => 'Gestion des utilisateurs',
                'route' => 'olix_users__list',
                'icon' => 'fas fa-users',
            ]));
        }

        $this->activateByRoute($event->getRequest()->get('_route'), $event->getSidebarMenu());
    }

    /**
     * Correspondance de la route par récursivité pour activer le menu en cours.
     *
     * @param string              $route
     * @param MenuItemInterface[] $items : MenuItemInterface[]
     */
    protected function activateByRoute(string $route, ?array $items): void
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                if ($item->getRoute() === $route) {
                    $item->setIsActive(true); // TODO inclusio de chemin __
                }
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() === $route) {
                $item->setIsActive(true);
            }
        }
    }
}
