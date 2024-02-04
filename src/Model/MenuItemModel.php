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
 * Classe de chaque élément composant la menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class MenuItemModel implements MenuItemInterface
{
    protected ?string $label;

    protected ?string $route;

    /**
     * @var array<mixed>
     */
    protected $routeArgs = [];

    protected bool $isActive = false;

    protected ?string $icon;

    protected ?string $iconColor;

    protected ?string $badge;

    protected ?string $badgeColor;

    /**
     * @var MenuItemInterface[]
     */
    protected $children = [];

    protected ?MenuItemInterface $parent = null;

    /**
     * Constructeur.
     *
     * @param string       $code    : Code identifiant ce menu
     * @param array<mixed> $options : Options du menu
     */
    public function __construct(protected string $code, array $options = [])
    {
        $this->label = $options['label'] ?? null;
        $this->route = $options['route'] ?? null;
        $this->routeArgs = $options['routeArgs'] ?? [];
        $this->icon = $options['icon'] ?? null;
        $this->iconColor = $options['iconColor'] ?? null;
        $this->badge = $options['badge'] ?? null;
        $this->badgeColor = $options['badgeColor'] ?? null;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

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
     */
    public function setRouteArgs(array $routeArgs): self
    {
        $this->routeArgs = $routeArgs;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        if ($this->hasParent()) {
            $this->getParent()->setIsActive($isActive);
        }

        $this->isActive = $isActive;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIconColor(): ?string
    {
        return $this->iconColor;
    }

    public function setIconColor(?string $iconColor): self
    {
        $this->iconColor = $iconColor;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(?string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getBadgeColor(): ?string
    {
        return $this->badgeColor;
    }

    public function setBadgeColor(?string $badgeColor): self
    {
        $this->badgeColor = $badgeColor;

        return $this;
    }

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

    public function getChild(string $code): MenuItemInterface
    {
        return $this->children[$code] ?? null;
    }

    public function addChild(MenuItemInterface $child): self
    {
        $child->setParent($this);
        $this->children[$child->getCode()] = $child;

        return $this;
    }

    /**
     * @param MenuItemInterface|string $child
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

    public function getActiveChild(): ?MenuItemInterface
    {
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return $child;
            }
        }

        return null;
    }

    public function hasParent(): bool
    {
        return $this->parent instanceof MenuItemInterface;
    }

    public function getParent(): ?MenuItemInterface
    {
        return $this->parent;
    }

    public function setParent(MenuItemInterface $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @see Countable::count()
     */
    public function count(): int
    {
        return count($this->children);
    }

    /**
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->getChildren());
    }
}
