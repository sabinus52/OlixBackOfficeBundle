<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Symfony\Component\Form\AbstractType;

/**
 * Abstract class for custom type.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 */
abstract class AbstractModelType extends AbstractType
{
    protected const COLORS = [
        'primary', 'blue', 'secondary', 'success', 'green', 'info', 'cyan', 'warning', 'yellow', 'danger', 'red',
        'black', 'gray-dark', 'gray', 'light', 'default',
        'indigo', 'navy', 'purple', 'fuchsia', 'pink', 'maroon', 'orange', 'lime', 'teal', 'olive',
    ];

    /**
     * Retourne toutes les options javascript d'un widget.
     * Parameters starts with "js_".
     *
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function getOptionsWidgetCamelized(array $options, string $prefix = 'js_'): array
    {
        $result = [];

        foreach ($options as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                // Remove 'js_' and camelize the options names
                $nameOption = substr($key, strlen($prefix));
                $nameOption = $this->camelize($nameOption);
                $result[$nameOption] = $value;
            }
        }

        return $result;
    }

    /**
     * Camelize a option name "days_of_week_disabled" -> "daysOfWeekDisabled".
     *
     * @return string
     */
    public function camelize(string $name): string
    {
        return preg_replace_callback(
            '/_([a-z])/',
            static fn (array $char): string => strtoupper($char[1]),
            $name
        );
    }
}
