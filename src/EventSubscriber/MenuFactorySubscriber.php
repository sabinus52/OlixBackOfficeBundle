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
use Olix\BackOfficeBundle\Event\BreadcrumbEvent;
use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Olix\BackOfficeBundle\Model\MenuItemInterface;
use Olix\BackOfficeBundle\Model\MenuItemModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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

    public function __construct(ParameterBagInterface $parameterBag, protected EntityManagerInterface $entityManager, private readonly Security $security)
    {
        // Get parameter "olix_back_office.security"
        if (!$parameterBag->has('olix_back_office')) {
            throw new \Exception('Parameter "olix_back_office" not defined', 1);
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
     */
    public function onBuildSidebar(SidebarMenuEvent $event): void
    {
        $this->build($event);

        // Add menu manage of users
        if ($this->security->isGranted('ROLE_ADMIN') && true === $this->parameters['menu_activ']) {
            $event->addMenuItem(new MenuItemModel('security', [
                'label' => 'Gestion des utilisateurs',
                'route' => 'olix_users__list',
                'icon' => 'fas fa-users',
            ]));
        }

        $this->activateByRoute($this->getPrefixRoute($event->getMenuActiv()), $event->getSidebarMenu());
    }

    /**
     * Correspondance de la route par récursivité pour activer le menu en cours.
     *
     * @param string              $match Chaine à faire correspondre
     * @param MenuItemInterface[] $items Les éléments du menu
     */
    protected function activateByRoute(string $match, ?array $items): void
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                if ($this->getPrefixRoute($item->getRoute()) === $match) {
                    $item->setIsActive(true);
                } elseif ($this->getPrefixRoute($item->getCode()) === $match) {
                    $item->setIsActive(true);
                }

                $this->activateByRoute($match, $item->getChildren());
            } elseif ($this->getPrefixRoute($item->getRoute()) === $match) {
                $item->setIsActive(true);
            } elseif ($this->getPrefixRoute($item->getCode()) === $match) {
                $item->setIsActive(true);
            }
        }
    }

    /**
     * Retourne la route ou le prefixe de la route avant '__' pour les sous pages du menu.
     */
    protected function getPrefixRoute(?string $route): ?string
    {
        if (null === $route) {
            return null;
        }

        $result = strstr($route, '__', true);

        return (false === $result) ? $route : $result.'__';
    }
}
