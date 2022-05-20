<?php

namespace Olix\BackOfficeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Olix\AdminLteFullBundle\DependencyInjection\OlixAdminFullExtension;

/**
 * Classe racine du bundle
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class OlixBackOfficeBundle extends Bundle
{
    /*public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $ext = new \Olix\AdminLteFullBundle\DependencyInjection\OlixAdminLteFullExtension([],$container);
    }*/


    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
