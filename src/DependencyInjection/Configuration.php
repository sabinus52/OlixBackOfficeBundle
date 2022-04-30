<?php
/**
 * Déclaration de la configuration du bundle
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 * @see https://symfony.com/doc/current/bundles/configuration.html
 */

namespace Olix\BackOfficeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{

    /**
     * Retourne les paramètres de la configuration complète du bundle
     * 
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder('olix_back_office');

        $treeBuilder->getRootNode()
            ->children()
                ->append($this->getOptionsConfig())
            ->end()
        ->end();

        return $treeBuilder;
    }


    /**
     * Retourne la configuration de la branche "options" du thème du layout
     * 
     * @return NodeDefinition
     */
    private function getOptionsConfig() : NodeDefinition
    {
        $treeBuilder = new TreeBuilder('options');
        $rootNode = $treeBuilder->getRootNode();

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
        ->end();

        return $rootNode;
    }

}