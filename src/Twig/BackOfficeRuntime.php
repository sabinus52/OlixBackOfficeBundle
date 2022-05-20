<?php
/**
 * Runtime des "filters" et "functions" personnalisés TWIG (pour la performance)
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 * @see https://symfony.com/doc/current/templating/twig_extension.html#creating-lazy-loaded-twig-extensions
 */

namespace Olix\BackOfficeBundle\Twig;

use Twig\Extension\RuntimeExtensionInterface;

/**
 * @SuppressWarnings(PHPMD)
 */
class BackOfficeRuntime implements RuntimeExtensionInterface
{

    /**
     * Configuration des options du thème
     * 
     * @var array
     */
    private $options;


    /**
     * @param array $configs : Configuration du bundle 'olix_back_office'
     */
    public function __construct(array $configs)
    {
        $this->options = (isset($configs['options'])) ? $configs['options'] : [];
    }


    /**
     * Retourne la liste des classes pour la balise BODY
     * 
     * @return string
     */
    public function getClassBody(): string
    {
        $classes = [];
        if ( isset($this->options['boxed']) && $this->options['boxed'] == true ) {
            $classes[] = 'layout-boxed';
        } else {
            if ( isset($this->options['navbar']['fixed']) && $this->options['navbar']['fixed'] == true ) {
                $classes[] = 'layout-navbar-fixed';
            }
            if ( isset($this->options['sidebar']['fixed']) && $this->options['sidebar']['fixed'] == true ) {
                $classes[] = 'layout-fixed';
            }
        }
        if ( isset($this->options['footer']['fixed']) && $this->options['footer']['fixed'] == true ) {
            $classes[] = 'layout-footer-fixed';
        }
        if ( isset($this->options['sidebar']['collapsed']) && $this->options['sidebar']['collapsed'] == true ) {
            $classes[] = 'sidebar-collapse';
        }
        if ( isset($this->options['sidebar']['color']) && $this->options['sidebar']['color'] != '' ) {
            $classes[] = 'accent-'.$this->options['sidebar']['color'];
        }
        if ( isset($this->options['dark_mode']) && $this->options['dark_mode'] != '' ) {
            $classes[] = 'dark-mode';
        }
        return implode(' ', $classes);
    }


    /**
     * Retourne la liste des classes pour la barre de navigation
     * 
     * @return string
     */
    public function getClassNavbar(): string
    {
        $classes = [];
        if ( isset($this->options['navbar']['theme']) && $this->options['navbar']['theme'] == 'dark' ) {
            $classes[] = 'navbar-dark';
        } else {
            $classes[] = 'navbar-light';
        }
        if ( isset($this->options['navbar']['color']) && $this->options['navbar']['color'] != '' ) {
            $classes[] = 'navbar-'.$this->options['navbar']['color'];
        }
        return implode(' ', $classes);
    }


    /**
     * Retourne la liste des classes pour la barre latérale
     * 
     * @return string
     */
    public function getClassSidebar(): string
    {
        $classes = [];
        if ( isset($this->options['sidebar']['theme']) && $this->options['sidebar']['theme'] == 'light' ) {
            if ( isset($this->options['sidebar']['color']) && $this->options['sidebar']['color'] != '' ) {
                $classes[] = 'sidebar-light-'.$this->options['sidebar']['color'];
            }
        } else {
            if ( isset($this->options['sidebar']['color']) && $this->options['sidebar']['color'] != '' ) {
                $classes[] = 'sidebar-dark-'.$this->options['sidebar']['color'];
            }
        }
        return implode(' ', $classes);
    }


    /**
     * Retourne la liste des classes pour le menu de la barre de navigation
     * 
     * @return string
     */
    public function getClassMenu(): string
    {
        $classes = [];
        if ( isset($this->options['sidebar']['flat']) && $this->options['sidebar']['flat'] == true ) {
            $classes[] = 'nav-flat';
        }
        if ( isset($this->options['sidebar']['legacy']) && $this->options['sidebar']['legacy'] == true ) {
            $classes[] = 'nav-legacy';
        }
        if ( isset($this->options['sidebar']['compact']) && $this->options['sidebar']['compact'] == true ) {
            $classes[] = 'nav-compact';
        }
        if ( isset($this->options['sidebar']['child_indent']) && $this->options['sidebar']['child_indent'] == true ) {
            $classes[] = 'nav-child-indent';
        }
        return implode(' ', $classes);
    }

}