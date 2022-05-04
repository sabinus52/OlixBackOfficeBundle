<?php
/**
 * Evènements sur le menu de la barre latérale
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;


class SidebarMenuEvent extends BackOfficeEvent
{

    /**
     * @var MenuItemInterface[]
     */
    private $rootItems = [];

    /**
     * @var Request
     */
    private $request;



    /**
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }


    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }


    /**
     * Retourne le menu de la barre latérale
     * 
     * @return array
     */
    public function getSidebarMenu(): array
    {
        return $this->rootItems;
    }


    /**
     * Ajoute un nouvel élémént de menu
     * 
     * @param MenuItemInterface $item
     * @return MenuEvent
     */
    public function addItem(MenuItemInterface $item)
    {
        $this->rootItems[$item->getCode()] = $item;

        return $this;
    }


    /**
     * Enlève un élément au menu
     * 
     * @param MenuItemInterface|string $item
     * @return MenuEvent
     */
    public function removeItem($item): MenuEvent
    {
        if ( $item instanceof MenuItemInterface && isset($this->rootItems[$item->getCode()]) ) {
            unset($this->rootItems[$item->getCode()]);
        } elseif ( is_string($item) && isset($this->rootItems[$item]) ) {
            unset($this->rootItems[$item]);
        }

        return $this;
    }

    /**
     * @param string $code
     * @return MenuItemInterface|null
     */
    public function getItem($code)
    {
        return $this->rootItems[$code] ?? null;
    }


    /**
     * Retourne le menu actif du niveau 1
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