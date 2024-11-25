<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe de chaque élément composant la menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface MenuItemInterface extends \Countable, \IteratorAggregate
{
    public function getCode(): string;

    public function getLabel(): ?string;

    public function getRoute(): ?string;

    /**
     * @return array<mixed>
     */
    public function getRouteArgs(): array;

    public function isActive(): bool;

    public function setIsActive(bool $isActive): self;

    public function getIcon(): ?string;

    public function getIconColor(): ?string;

    public function getBadge(): ?string;

    public function getBadgeColor(): ?string;

    public function hasChildren(): bool;

    /**
     * @return MenuItemInterface[]
     */
    public function getChildren(): array;

    public function getChild(string $code): ?self;

    public function getActiveChild(): ?self;

    public function addChild(self $child): self;

    /**
     * @param MenuItemInterface|string $child
     */
    public function removeChild($child): self;

    public function hasParent(): bool;

    public function getParent(): ?self;

    public function setParent(?self $parent = null): self;
}
