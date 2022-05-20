<?php

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe de chaque élément composant la menu de la barre latérale
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface MenuItemInterface extends \Countable, \IteratorAggregate
{
    /**
     * @return string
     */
    public function getCode(): string;


    /**
     * @return string
     */
    public function getLabel(): string;


    /**
     * @return string
     */
    public function getRoute(): ?string;


    /**
     * @return array
     */
    public function getRouteArgs(): array;


    /**
     * @return boolean
     */
    public function isActive(): bool;


    /**
     * @return string
     */
    public function getIcon(): ?string;


    /**
     * @return string
     */
    public function getIconColor(): ?string;


    /**
     * @return string
     */
    public function getBadge(): ?string;


    /**
     * @return string
     */
    public function getBadgeColor(): ?string;


    /**
     * @return bool
     */
    public function hasChildren(): bool;


    /**
     * @return array
     */
    public function getChildren(): array;


    /**
     * @param MenuItemInterface $child
     * @return MenuItemInterface
     */
    public function addChild(MenuItemInterface $child): MenuItemInterface;


    /**
     * @param MenuItemInterface $child
     * @return MenuItemInterface
     */
    public function removeChild(MenuItemInterface $child): MenuItemInterface;


    /**
     * @return bool
     */
    public function hasParent(): bool;


    /**
     * @return MenuItemInterface
     */
    public function getParent(): ?MenuItemInterface;


    /**
     * @param MenuItemInterface $parent
     * @return MenuItemInterface
     */
    public function setParent(MenuItemInterface $parent = null): MenuItemInterface;
}
