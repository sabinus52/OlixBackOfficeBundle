<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Enum;

trait EnumTrait
{
    /**
     * Retourne la liste des noms de l'énumération.
     *
     * @return array<string|int>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Retourne la liste des valeurs de l'énumération.
     *
     * @return array<string|int>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Retourne un tableau associatif [value] => [name].
     *
     * @return array<string|int,string|int>
     */
    public static function asArray(): array
    {
        if (empty(self::values())) {
            return self::names();
        }

        if (empty(self::names())) {
            return self::values();
        }

        return array_column(self::cases(), 'value', 'name');
    }
}
