<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Déclaration de la configuration du bundle.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @see        https://symfony.com/doc/current/bundles/configuration.html
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Retourne les paramètres de la configuration complète du bundle.
     *
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('olix_back_office');

        // @phpstan-ignore-next-line
        $treeBuilder->getRootNode()
            ->children()
            ->append($this->getOptionsConfig())
            ->append($this->getSecurityConfig())
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Retourne la configuration de la branche "options" du thème du layout.
     *
     * @return NodeDefinition
     */
    private function getOptionsConfig(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('options');
        $rootNode = $treeBuilder->getRootNode();

        // @phpstan-ignore-next-line
        $rootNode
            ->children()
            ->booleanNode('dark_mode')->defaultValue(false)->end()
            ->booleanNode('boxed')->defaultValue(false)->end()
            ->arrayNode('navbar')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('fixed')->defaultValue(false)->end()
            ->scalarNode('theme')->defaultValue('light')->end()
            ->scalarNode('color')->defaultValue('')->end()
            ->end()
            ->end()
            ->arrayNode('sidebar')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('fixed')->defaultValue(true)->end()
            ->booleanNode('collapse')->defaultValue(false)->end()
            ->scalarNode('theme')->defaultValue('dark')->end()
            ->scalarNode('color')->defaultValue('primary')->end()
            ->booleanNode('flat')->defaultValue(false)->end()
            ->booleanNode('legacy')->defaultValue(false)->end()
            ->booleanNode('compact')->defaultValue(false)->end()
            ->booleanNode('child_indent')->defaultValue(false)->end()
            ->end()
            ->end()
            ->arrayNode('footer')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('fixed')->defaultValue(false)->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $rootNode;
    }

    /**
     * Retourne la configuration de la branche "security" sur la gestions des utilisateurs.
     *
     * @return NodeDefinition
     */
    private function getSecurityConfig(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('security');
        $rootNode = $treeBuilder->getRootNode();

        // @phpstan-ignore-next-line
        $rootNode
            ->children()
            ->booleanNode('menu_activ')->defaultValue(false)->end()
            ->arrayNode('class')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('user')->defaultValue('App\Entity\User')->end()
            ->scalarNode('form_user')->defaultValue('Olix\BackOfficeBundle\Form\UserEditType')->end()
            ->scalarNode('form_profile')->defaultValue('Olix\BackOfficeBundle\Form\UserProfileType')->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $rootNode;
    }
}
