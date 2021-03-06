<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class Helper
{
    /**
     * Generate a unique ID.
     *
     * @param string $prefix
     *
     * @return string
     */
    public static function generateUniqueID($prefix = '')
    {
        $id = sha1(microtime(true).random_int(10000, 90000));

        return $prefix ? $prefix.'-'.$id : $id;
    }

    /**
     * Returns a array notated property path for the Accessor.
     *
     * @param string      $data
     * @param string|null $value
     *
     * @return string
     */
    public static function getDataPropertyPath($data, &$value = null)
    {
        // handle nested array case
        if (true === \is_int(strpos($data, '['))) {
            $before = strstr($data, '[', true);
            $value = strstr($data, ']', false);

            // remove needle
            $value = str_replace('].', '', $value); /** @phpstan-ignore-line */
            $value = '['.str_replace('.', '][', $value).']';

            $data = $before;
        }

        // e.g. 'createdBy.allowed' => [createdBy][allowed]
        return '['.str_replace('.', '][', $data).']'; // @phpstan-ignore-line
    }

    /**
     * Returns object notated property path.
     *
     * @param string $path
     * @param int    $key
     * @param string $value
     *
     * @return string
     */
    public static function getPropertyPathObjectNotation($path, $key, $value)
    {
        $objectValue = str_replace('][', '.', $value);
        $objectValue = str_replace(['[', ']'], '', $objectValue);

        return str_replace(['[', ']'], '', $path).'['.$key.'].'.$objectValue;
    }
}
