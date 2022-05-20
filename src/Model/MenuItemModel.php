<?php
/**
 * Classe de chaque élément composant la menu de la barre latérale
 * 
 * @author Olivier <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Model;

use ArrayIterator;

class MenuItemModel implements MenuItemInterface
{

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $label = null;

    /**
     * @var string
     */
    protected $route = null;

    /**
     * @var array
     */
    protected $routeArgs = [];

    /**
     * @var bool
     */
    protected $isActive = false;

    /**
     * @var string
     */
    protected $icon = null;

    /**
     * @var string
     */
    protected $iconColor = null;

    /**
     * @var string
     */
    protected $badge = null;

    /**
     * @var string
     */
    protected $badgeColor = null;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var MenuItemInterface
     */
    protected $parent = null;


    /**
     * Constructeur
     * 
     * @param string $code : Code identifiant ce menu
     * @param array $options : Options du menu
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
     * @return MenuItemInterface
     */
    public function setLabel(string $label): MenuItemInterface
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
     * @return MenuItemInterface
     */
    public function setRoute(?string $route): MenuItemInterface
    {
        $this->route = $route;
        return $this;
    }


    /**
     * @return array
     */
    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }

    /**
     * @param array $args
     * @return MenuItemInterface
    */
    public function setRouteArgs(array $routeArgs): MenuItemInterface
    {
        $this->routeArgs = $routeArgs;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     * @return MenuItemInterface
     */
    public function setIsActive(bool $isActive): MenuItemInterface
    {
        if ( $this->hasParent() ) {
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
     * @return MenuItemInterface
     */
    public function setIcon(?string $icon): MenuItemInterface
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
     * @return MenuItemInterface
     */
    public function setIconColor(?string $iconColor): MenuItemInterface
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
     * @return MenuItemInterface
     */
    public function setBadge(?string $badge): MenuItemInterface
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
     * @return MenuItemInterface
     */
    public function setBadgeColor(?string $badgeColor): MenuItemInterface
    {
        $this->badgeColor = $badgeColor;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return ( count($this->children) > 0 );
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param string $code
     * @return MenuItemInterface
     */
    public function getChild(string $code): MenuItemInterface
    {
        return $this->children[$code] ?? null;
    }

    /**
     * @param MenuItemInterface $child
     * @return MenuItemInterface
     */
    public function addChild(MenuItemInterface $child): MenuItemInterface
    {
        $child->setParent($this);
        $this->children[$child->getCode()] = $child;

        return $this;
    }

    /**
     * @param MenuItemInterface|string $child
     * @return MenuItemInterface
     */
    public function removeChild($child): MenuItemInterface
    {
        if ( $child instanceof MenuItemInterface && isset($this->children[$child->getCode()]) ) {
            $this->children[$child->getCode()]->setParent(null);
            unset($this->children[$child->getCode()]);
        } elseif ( is_string($child) && isset($this->children[$child]) ) {
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
     * @return MenuItemInterface
     */
    public function setParent(MenuItemInterface $parent = null): MenuItemInterface
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