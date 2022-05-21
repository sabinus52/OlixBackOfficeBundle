<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe de chaque élément composant la menu de la barre latérale.
 *
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
     * @return array<mixed>
     */
    public function getRouteArgs(): array;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @param bool $isActive
     *
     * @return MenuItemModel
     */
    public function setIsActive(bool $isActive): self;

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
     * @return MenuItemInterface[]
     */
    public function getChildren(): array;

    /**
     * @param MenuItemInterface $child
     *
     * @return MenuItemInterface
     */
    public function addChild(self $child): self;

    /**
     * @param MenuItemInterface $child
     *
     * @return MenuItemInterface
     */
    public function removeChild(self $child): self;

    /**
     * @return bool
     */
    public function hasParent(): bool;

    /**
     * @return MenuItemInterface
     */
    public function getParent(): ?self;

    /**
     * @param MenuItemInterface $parent
     *
     * @return MenuItemInterface
     */
    public function setParent(self $parent = null): self;
}
