<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable;

use Exception;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class Factory
{
    /**
     * Create.
     *
     * @param mixed $class
     * @param mixed $interface
     *
     * @throws Exception
     *
     * @return mixed
     */
    public static function create($class, $interface)
    {
        if (empty($class) || !\is_string($class) && !$class instanceof $interface) {
            throw new Exception("Factory::create(): String or {$interface} expected.");
        }

        if ($class instanceof $interface) {
            return $class;
        }

        if (\is_string($class) && class_exists($class)) {
            $instance = new $class();

            if (!$instance instanceof $interface) {
                throw new Exception("Factory::create(): String or {$interface} expected.");
            }

            return $instance;
        }

        throw new Exception("Factory::create(): {$class} is not callable.");
    }
}
