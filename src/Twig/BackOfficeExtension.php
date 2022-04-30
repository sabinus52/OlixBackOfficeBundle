<?php
/**
 * Extension des "filters" et "functions" personnalisés TWIG
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 * @see https://symfony.com/doc/current/templating/twig_extension.html
 */

namespace Olix\BackOfficeBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


class BackOfficeExtension extends AbstractExtension
{

    /**
     * Déclaration des fonctions Twig
     * 
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('olixbo_class_body', [BackOfficeRuntime::class, 'getClassBody']),
            new TwigFunction('olixbo_class_navbar', [BackOfficeRuntime::class, 'getClassNavbar']),
            new TwigFunction('olixbo_class_sidebar', [BackOfficeRuntime::class, 'getClassSidebar']),
            new TwigFunction('olixbo_class_menu', [BackOfficeRuntime::class, 'getClassMenu']),
        ];
    }
    
}