<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Olix\BackOfficeBundle\Helper\Helper;
use Symfony\Component\Form\AbstractType;

/**
 * Abstract class for custom type.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
abstract class AbstractModelType extends AbstractType
{
    /**
     * Clé pour les options JavaScript à surcharger dans les widgets.
     */
    public const KEY_OPTS_JS = 'options_js';

    /**
     * Attribut `data` pour les options JavaScript dans les widgets.
     * Ex: <input type="text" data-options-js="{'foo': 'bar'}">.
     */
    public const ATTR_DATA_OPTIONS = 'data-options-js';

    /**
     * Attribut javascript `data-toggle` pour les widgets.
     * Ex: <input type="text" data-toggle="foo">.
     */
    public const ATTR_DATA_SELECTOR = 'data-toggle';

    /**
     * Liste des couleurs disponibles pour les widgets.
     */
    protected const COLORS = [
        'primary', 'blue', 'secondary', 'success', 'green', 'info', 'cyan', 'warning', 'yellow', 'danger', 'red',
        'black', 'gray-dark', 'gray', 'light', 'default',
        'indigo', 'navy', 'purple', 'fuchsia', 'pink', 'maroon', 'orange', 'lime', 'teal', 'olive',
    ];

    /**
     * Retourne toutes les options javascript d'un widget.
     *
     * Parameters starts with "js_".
     *
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function getOptionsJavascriptCamelized(array $options): array
    {
        return $this->getOptionsWidgetCamelized($options, 'js_');
    }

    /**
     * Retourne toutes les options d'un widget camelisée.
     *
     * @param array<string,mixed> $options
     *
     * @return array<string,mixed>
     */
    protected function getOptionsWidgetCamelized(array $options, string $prefix = ''): array
    {
        return Helper::getCamelizedKeys($options, $prefix);
    }
}
