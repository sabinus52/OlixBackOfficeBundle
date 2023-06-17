<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Evènements sur le menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class SidebarMenuEvent extends BackOfficeEvent
{
    /**
     * @var MenuItemInterface[]
     */
    protected $rootItems = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * Code du menu pour forcer son activation.
     *
     * @var string
     */
    protected $forceMenuActiv;

    /**
     * @param Request     $request
     * @param string|null $forceMenuActiv
     */
    public function __construct(Request $request = null, ?string $forceMenuActiv = null)
    {
        $this->request = $request;
        $this->forceMenuActiv = (empty($forceMenuActiv)) ? null : $forceMenuActiv;
    }

    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * Retourne le code à correspondre pour mettre en surbrillance le menu de la sidebar.
     *
     * @return string
     */
    public function getMenuActiv(): string
    {
        return (null === $this->forceMenuActiv) ? $this->request->get('_route') : $this->forceMenuActiv;
    }

    /**
     * Retourne le menu de la barre latérale.
     *
     * @return MenuItemInterface[]
     */
    public function getSidebarMenu(): array
    {
        return $this->rootItems;
    }

    /**
     * Ajoute un nouvel élémént de menu.
     *
     * @param MenuItemInterface $item
     *
     * @return SidebarMenuEvent
     */
    public function addItem(MenuItemInterface $item): self
    {
        $this->rootItems[$item->getCode()] = $item;

        return $this;
    }

    /**
     * Enlève un élément au menu.
     *
     * @param MenuItemInterface|string $item
     *
     * @return SidebarMenuEvent
     */
    public function removeItem($item): self
    {
        if ($item instanceof MenuItemInterface && isset($this->rootItems[$item->getCode()])) {
            unset($this->rootItems[$item->getCode()]);
        } elseif (is_string($item) && isset($this->rootItems[$item])) {
            unset($this->rootItems[$item]);
        }

        return $this;
    }

    /**
     * @param string $code
     *
     * @return MenuItemInterface|null
     */
    public function getItem($code): ?MenuItemInterface
    {
        return $this->rootItems[$code] ?? null;
    }

    /**
     * Retourne le menu actif du niveau 1.
     *
     * @return MenuItemInterface|null
     */
    public function getActive(): ?MenuItemInterface
    {
        foreach ($this->getSidebarMenu() as $item) {
            if ($item->isActive()) {
                return $item;
            }
        }

        return null;
    }
}
