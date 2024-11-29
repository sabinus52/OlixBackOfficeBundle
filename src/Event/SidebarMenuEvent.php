<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Évènements sur le menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class SidebarMenuEvent extends BackOfficeEvent
{
    /**
     * @var MenuItemModel[]
     */
    protected $rootItems = [];

    public function __construct(protected Request $request, protected ?string $forceMenuActiv = null)
    {
        $this->forceMenuActiv = (empty($forceMenuActiv)) ? null : $forceMenuActiv;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Retourne le code à correspondre pour mettre en surbrillance le menu de la sidebar.
     */
    public function getMenuActiv(): string
    {
        return $this->forceMenuActiv ?? (string) $this->request->get('_route'); // @phpstan-ignore cast.string
    }

    /**
     * Retourne le menu de la barre latérale.
     *
     * @return MenuItemModel[]
     */
    public function getSidebarMenu(): array
    {
        return $this->rootItems;
    }

    /**
     * @deprecated use "addMenuItem"
     */
    public function addItem(MenuItemModel $item): self
    {
        return $this->addMenuItem($item);
    }

    /**
     * Ajoute un nouvel élément de menu.
     */
    public function addMenuItem(MenuItemModel $item): self
    {
        $this->rootItems[$item->getCode()] = $item;

        return $this;
    }

    /**
     * @deprecated use "removeMenuItem"
     *
     * @param MenuItemModel|string $item
     */
    public function removeItem($item): self
    {
        return $this->removeMenuItem($item);
    }

    /**
     * Enlève un élément au menu.
     *
     * @param MenuItemModel|string $item
     */
    public function removeMenuItem($item): self
    {
        if ($item instanceof MenuItemModel && isset($this->rootItems[$item->getCode()])) {
            unset($this->rootItems[$item->getCode()]);
        } elseif (is_string($item) && isset($this->rootItems[$item])) {
            unset($this->rootItems[$item]);
        }

        return $this;
    }

    /**
     * @deprecated use "getMenuItem"
     */
    public function getItem(string $code): ?MenuItemModel
    {
        return $this->getMenuItem($code);
    }

    /**
     * Retourne l'item en fonction de son code.
     */
    public function getMenuItem(string $code): ?MenuItemModel
    {
        return $this->rootItems[$code] ?? null;
    }

    /**
     * @deprecated  use "getMenuItemActive"
     */
    public function getActive(): ?MenuItemModel
    {
        return $this->getMenuItemActive();
    }

    /**
     * Retourne le menu actif du niveau 1.
     */
    public function getMenuItemActive(): ?MenuItemModel
    {
        foreach ($this->getSidebarMenu() as $item) {
            if ($item->isActive()) {
                return $item;
            }
        }

        return null;
    }
}
