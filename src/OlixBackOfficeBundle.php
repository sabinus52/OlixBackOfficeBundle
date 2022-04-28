<?php

namespace Olix\BackOfficeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Olix\AdminLteFullBundle\DependencyInjection\OlixAdminFullExtension;


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
