<?php

namespace Olix\BackOfficeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Chargement de la configuration du bundle
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 * @see        https://symfony.com/doc/current/bundles/extension.html
 */
class OlixBackOfficeExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @phpstan-ignore-next-line
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
