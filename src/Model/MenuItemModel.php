<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

use Olix\BackOfficeBundle\Enum\ColorBS;
use Olix\BackOfficeBundle\Enum\ColorCSS;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Classe de chaque élément composant la menu de la barre latérale.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 *
 * @implements \IteratorAggregate<string, MenuItemModel>
 */
class MenuItemModel implements \Countable, \IteratorAggregate
{
    protected bool $isActive = false;

    /**
     * @var array<string,string|null>
     */
    protected array $options = [];

    /**
     * @var MenuItemModel[]
     */
    protected $children = [];

    protected ?MenuItemModel $parent = null;

    /**
     * Constructeur.
     *
     * @param string       $code    : Code identifiant ce menu
     * @param array<mixed> $options : Options du menu
     */
    public function __construct(protected string $code, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        // @deprecated 1.2
        if (array_key_exists('routeArgs', $this->options)) {
            $this->options['route_args'] = $this->options['routeArgs'];
            unset($this->options['routeArgs']);
        }
        if (array_key_exists('iconColor', $this->options)) {
            $this->options['icon_color'] = $this->options['iconColor'];
            unset($this->options['iconColor']);
        }
        if (array_key_exists('badgeColor', $this->options)) {
            $this->options['badge_color'] = $this->options['badgeColor'];
            unset($this->options['badgeColor']);
        }
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('label', null);
        $resolver->setDefault('route', null);
        $resolver->setDefault('route_args', []);
        $resolver->setDefault('icon', null);
        $resolver->setDefault('icon_color', null);
        $resolver->setDefault('image', null);
        $resolver->setDefault('badge', null);
        $resolver->setDefault('badge_color', null);

        $resolver->setRequired('label');
        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('route', ['string', 'null']);
        $resolver->setAllowedTypes('route_args', 'array');
        $resolver->setAllowedTypes('icon', ['string', 'null']);
        $resolver->setAllowedTypes('icon_color', ['string', 'null']);
        $resolver->setAllowedTypes('image', ['string', 'null']);
        $resolver->setAllowedTypes('badge', ['string', 'null']);
        $resolver->setAllowedTypes('badge_color', ['string', 'null']);

        $resolver->setAllowedValues('icon_color', [null] + array_merge(ColorCSS::values(), ColorBS::values()));
        $resolver->setAllowedValues('badge_color', [null] + array_merge(ColorCSS::values(), ColorBS::values()));

        // @deprecated 1.2
        $resolver->setDefined([
            'routeArgs',
            'iconColor',
            'badgeColor',
        ]);
        $resolver->setDeprecated('routeArgs', 'olix/backoffice-bundle', '1.2', 'The "routeArgs" option is deprecated. Use "route_args" instead.');
        $resolver->setDeprecated('iconColor', 'olix/backoffice-bundle', '1.2', 'The "iconColor" option is deprecated. Use "icon_color" instead.');
        $resolver->setDeprecated('badgeColor', 'olix/backoffice-bundle', '1.2', 'The "badgeColor" option is deprecated. Use "badge_color" instead.');
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRoute(): ?string
    {
        return $this->options['route'];
    }

    public function __call(string $name, mixed $arguments): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        return $this->options[$name] ?? null;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        if ($this->hasParent() && $this->getParent() instanceof static) {
            $this->getParent()->setIsActive($isActive);
        }

        $this->isActive = $isActive;

        return $this;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * @return MenuItemModel[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function getChild(string $code): ?self
    {
        return $this->children[$code] ?? null;
    }

    public function addChild(self $child): static
    {
        $child->setParent($this);
        $this->children[$child->getCode()] = $child;

        return $this;
    }

    /**
     * @param MenuItemModel|string $child
     */
    public function removeChild($child): static
    {
        if ($child instanceof self && isset($this->children[$child->getCode()])) {
            $this->children[$child->getCode()]->setParent(null);
            unset($this->children[$child->getCode()]);
        } elseif (is_string($child) && isset($this->children[$child])) {
            $this->children[$child]->setParent(null);
            unset($this->children[$child]);
        }

        return $this;
    }

    public function getActiveChild(): ?self
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
        return $this->parent instanceof static;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent = null): static
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
