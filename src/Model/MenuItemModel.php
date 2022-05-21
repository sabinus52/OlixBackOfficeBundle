<?php

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

use ArrayIterator;

/**
 * Classe de chaque élément composant la menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class MenuItemModel implements MenuItemInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var array<mixed>
     */
    protected $routeArgs = [];

    /**
     * @var bool
     */
    protected $isActive = false;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $iconColor;

    /**
     * @var string
     */
    protected $badge;

    /**
     * @var string
     */
    protected $badgeColor;

    /**
     * @var MenuItemInterface[]
     */
    protected $children = [];

    /**
     * @var MenuItemInterface
     */
    protected $parent;

    /**
     * Constructeur.
     *
     * @param string       $code    : Code identifiant ce menu
     * @param array<mixed> $options : Options du menu
     */
    public function __construct(string $code, array $options = [])
    {
        $this->code = $code;
        $this->label = $options['label'] ?? null;
        $this->route = $options['route'] ?? null;
        $this->routeArgs = $options['routeArgs'] ?? [];
        $this->icon = $options['icon'] ?? null;
        $this->iconColor = $options['iconColor'] ?? null;
        $this->badge = $options['badge'] ?? null;
        $this->badgeColor = $options['badgeColor'] ?? null;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return MenuItemModel
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param string $route
     *
     * @return MenuItemModel
     */
    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }

    /**
     * @param array<mixed> $routeArgs
     *
     * @return MenuItemModel
     */
    public function setRouteArgs(array $routeArgs): self
    {
        $this->routeArgs = $routeArgs;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return MenuItemModel
     */
    public function setIsActive(bool $isActive): self
    {
        if ($this->hasParent()) {
            $this->getParent()->setIsActive($isActive);
        }
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return MenuItemModel
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconColor(): ?string
    {
        return $this->iconColor;
    }

    /**
     * @param string $iconColor
     *
     * @return MenuItemModel
     */
    public function setIconColor(?string $iconColor): self
    {
        $this->iconColor = $iconColor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBadge(): ?string
    {
        return $this->badge;
    }

    /**
     * @param string $badge
     *
     * @return MenuItemModel
     */
    public function setBadge(?string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBadgeColor(): ?string
    {
        return $this->badgeColor;
    }

    /**
     * @param string $badgeColor
     *
     * @return MenuItemModel
     */
    public function setBadgeColor(?string $badgeColor): self
    {
        $this->badgeColor = $badgeColor;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * @return MenuItemInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param string $code
     *
     * @return MenuItemInterface
     */
    public function getChild(string $code): MenuItemInterface
    {
        return $this->children[$code] ?? null;
    }

    /**
     * @param MenuItemInterface $child
     *
     * @return MenuItemModel
     */
    public function addChild(MenuItemInterface $child): self
    {
        $child->setParent($this);
        $this->children[$child->getCode()] = $child;

        return $this;
    }

    /**
     * @param MenuItemInterface|string $child
     *
     * @return MenuItemModel
     */
    public function removeChild($child): self
    {
        if ($child instanceof MenuItemInterface && isset($this->children[$child->getCode()])) {
            $this->children[$child->getCode()]->setParent(null);
            unset($this->children[$child->getCode()]);
        } elseif (is_string($child) && isset($this->children[$child])) {
            $this->children[$child]->setParent(null);
            unset($this->children[$child]);
        }

        return $this;
    }

    /**
     * @return MenuItemInterface|null
     */
    public function getActiveChild(): ?MenuItemInterface
    {
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return $child;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasParent(): bool
    {
        return $this->parent instanceof MenuItemInterface;
    }

    /**
     * @return MenuItemInterface
     */
    public function getParent(): ?MenuItemInterface
    {
        return $this->parent;
    }

    /**
     * @param MenuItemInterface $parent
     *
     * @return MenuItemModel
     */
    public function setParent(MenuItemInterface $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getChildren());
    }
}
