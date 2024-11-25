<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Twig;

use Olix\BackOfficeBundle\Model\User;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Runtime des "filters" et "functions" personnalisés TWIG (pour la performance).
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @see        https://symfony.com/doc/current/templating/twig_extension.html#creating-lazy-loaded-twig-extensions
 *
 * @SuppressWarnings(PHPMD)
 */
class BackOfficeRuntime implements RuntimeExtensionInterface
{
    /**
     * Configuration des options du thème.
     *
     * @var array<mixed>
     */
    private $options;

    /**
     * @param array<mixed> $olixConfigParameter : Configuration du bundle 'olix_back_office'
     */
    public function __construct(array $olixConfigParameter)
    {
        $this->options = $olixConfigParameter['options'] ?? [];
    }

    /**
     * Retourne la liste des classes pour la balise BODY.
     */
    public function getClassBody(?User $user): string
    {
        $classes = [];
        if (isset($this->options['boxed']) && true === $this->options['boxed']) {
            $classes[] = 'layout-boxed';
        } else {
            if (isset($this->options['navbar']['fixed']) && true === $this->options['navbar']['fixed']) {
                $classes[] = 'layout-navbar-fixed';
            }

            if (isset($this->options['sidebar']['fixed']) && true === $this->options['sidebar']['fixed']) {
                $classes[] = 'layout-fixed';
            }
        }

        if (isset($this->options['footer']['fixed']) && true === $this->options['footer']['fixed']) {
            $classes[] = 'layout-footer-fixed';
        }

        if (isset($this->options['sidebar']['collapsed']) && true === $this->options['sidebar']['collapsed']) {
            $classes[] = 'sidebar-collapse';
        }

        if (isset($this->options['sidebar']['color']) && '' !== $this->options['sidebar']['color']) {
            $classes[] = 'accent-'.$this->options['sidebar']['color'];
        }

        if ($user instanceof User) {
            if (User::THEME_DARK === $user->getTheme()) {
                $classes[] = 'dark-mode';
            }
        } elseif (isset($this->options['dark_mode']) && true === $this->options['dark_mode']) {
            $classes[] = 'dark-mode';
        }

        return implode(' ', $classes);
    }

    /**
     * Retourne la liste des classes pour la barre de navigation.
     */
    public function getClassNavbar(): string
    {
        $classes = [];
        if (isset($this->options['navbar']['theme']) && 'dark' === $this->options['navbar']['theme']) {
            $classes[] = 'navbar-dark';
        } else {
            $classes[] = 'navbar-light';
        }

        if (isset($this->options['navbar']['color']) && '' !== $this->options['navbar']['color']) {
            $classes[] = 'navbar-'.$this->options['navbar']['color'];
        }

        return implode(' ', $classes);
    }

    /**
     * Retourne la liste des classes pour la barre latérale.
     */
    public function getClassSidebar(): string
    {
        $classes = [];
        if (isset($this->options['sidebar']['theme']) && 'light' === $this->options['sidebar']['theme']) {
            if (isset($this->options['sidebar']['color']) && '' !== $this->options['sidebar']['color']) {
                $classes[] = 'sidebar-light-'.$this->options['sidebar']['color'];
            }
        } elseif (isset($this->options['sidebar']['color']) && '' !== $this->options['sidebar']['color']) {
            $classes[] = 'sidebar-dark-'.$this->options['sidebar']['color'];
        }

        return implode(' ', $classes);
    }

    /**
     * Retourne la liste des classes pour le menu de la barre de navigation.
     */
    public function getClassMenu(): string
    {
        $classes = [];
        if (isset($this->options['sidebar']['flat']) && true === $this->options['sidebar']['flat']) {
            $classes[] = 'nav-flat';
        }

        if (isset($this->options['sidebar']['legacy']) && true === $this->options['sidebar']['legacy']) {
            $classes[] = 'nav-legacy';
        }

        if (isset($this->options['sidebar']['compact']) && true === $this->options['sidebar']['compact']) {
            $classes[] = 'nav-compact';
        }

        if (isset($this->options['sidebar']['child_indent']) && true === $this->options['sidebar']['child_indent']) {
            $classes[] = 'nav-child-indent';
        }

        return implode(' ', $classes);
    }
}
