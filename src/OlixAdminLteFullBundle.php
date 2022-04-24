<?php

namespace Olix\AdminLteFullBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class OlixAdminLteFullBundle extends Bundle
{

    public function getPath(): string
    {
        return dirname(__DIR__);
    }

}
