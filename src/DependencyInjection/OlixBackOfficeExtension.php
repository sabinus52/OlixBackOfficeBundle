<?php
/**
 * Chargement de la configuration du bundle
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 * @see https://symfony.com/doc/current/bundles/extension.html
 */

namespace Olix\BackOfficeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class OlixBackOfficeExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Chargement du fichier de configuration des services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yml');

        // Affecte la configuration par défaut du bundle dans le paramètre global "olix_back_office"
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('olix_back_office', $config);
    }

}