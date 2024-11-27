<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Twig;

use Olix\BackOfficeBundle\Helper\ParameterOlix;
use Olix\BackOfficeBundle\Model\User;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Runtime des "filters" et "functions" personnalisés TWIG (pour la performance).
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @see        https://symfony.com/doc/current/templating/twig_extension.html#creating-lazy-loaded-twig-extensions
 */
class BackOfficeRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly ParameterOlix $configuration)
    {
    }

    /**
     * Retourne la liste des classes pour la balise BODY.
     */
    public function getClassBody(?User $user): string
    {
        $classes = [];
        if (true === $this->configuration->getValue('options.boxed')) {
            $classes[] = 'layout-boxed';
        } else {
            if (true === $this->configuration->getValue('options.navbar.fixed')) {
                $classes[] = 'layout-navbar-fixed';
            }
            if (true === $this->configuration->getValue('options.sidebar.fixed')) {
                $classes[] = 'layout-fixed';
            }
        }

        if (true === $this->configuration->getValue('options.footer.fixed')) {
            $classes[] = 'layout-footer-fixed';
        }

        if (true === $this->configuration->getValue('options.sidebar.collapse')) {
            $classes[] = 'sidebar-collapse';
        }

        if ('' !== $this->configuration->getValue('options.sidebar.color')) {
            $classes[] = 'accent-'.$this->configuration->getValue('options.sidebar.color');
        }

        // Si theme dark ou light
        if (null !== $this->getClassBodyUser($user)) {
            $classes[] = $this->getClassBodyUser($user);
        }

        return implode(' ', $classes);
    }

    /**
     * Retourne si c'est le thème "dark" qui est utilisé par l'utilisateur.
     */
    private function getClassBodyUser(?User $user): ?string
    {
        if ($user instanceof User) {
            if (User::THEME_DARK === $user->getTheme()) {
                return 'dark-mode';
            }
        } elseif (true === $this->configuration->getValue('options.dark_mode')) {
            return 'dark-mode';
        }

        return null;
    }

    /**
     * Retourne la liste des classes pour la barre de navigation.
     */
    public function getClassNavbar(): string
    {
        $classes = [];
        $classes[] = 'dark' === $this->configuration->getValue('options.navbar.theme') ? 'navbar-dark' : 'navbar-light';

        if ('' !== $this->configuration->getValue('options.navbar.color')) {
            $classes[] = 'navbar-'.$this->configuration->getValue('options.navbar.color');
        }

        return implode(' ', $classes);
    }

    /**
     * Retourne la liste des classes pour la barre latérale.
     */
    public function getClassSidebar(): string
    {
        $classes = [];
        if ('light' === $this->configuration->getValue('options.sidebar.theme')) {
            if ('' !== $this->configuration->getValue('options.sidebar.color')) {
                $classes[] = 'sidebar-light-'.$this->configuration->getValue('options.sidebar.color');
            }
        } elseif ('' !== $this->configuration->getValue('options.sidebar.color')) {
            $classes[] = 'sidebar-dark-'.$this->configuration->getValue('options.sidebar.color');
        }

        return implode(' ', $classes);
    }

    /**
     * Retourne la liste des classes pour le menu de la barre de navigation.
     */
    public function getClassMenu(): string
    {
        $classes = [];
        if (true === $this->configuration->getValue('options.sidebar.flat')) {
            $classes[] = 'nav-flat';
        }

        if (true === $this->configuration->getValue('options.sidebar.legacy')) {
            $classes[] = 'nav-legacy';
        }

        if (true === $this->configuration->getValue('options.sidebar.compact')) {
            $classes[] = 'nav-compact';
        }

        if (true === $this->configuration->getValue('options.sidebar.child_indent')) {
            $classes[] = 'nav-child-indent';
        }

        return implode(' ', $classes);
    }
}
