<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Classe racine du bundle.
 *
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
