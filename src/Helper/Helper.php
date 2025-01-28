<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Helper;

/**
 * Classe utilitaire.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
final class Helper
{
    /**
     * Camelize les clés d'un tableau uniquement pour les clés qui commencent par le préfixe.
     *
     * @param array<mixed> $array
     * @param string       $prefix
     *
     * @return array<mixed>
     */
    public static function getCamelizedKeys(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            // Ignorer les clés numériques
            if (is_int($key)) {
                $result[$key] = $value;
                continue;
            }

            // Prendre en compte les clés qui commencent par le préfixe
            if (str_starts_with($key, $prefix)) {
                // Convertir la clé en camelCase
                $camelizedKey = substr($key, strlen($prefix));
                $camelizedKey = self::camelize($camelizedKey);
            } else {
                $camelizedKey = $key;
            }

            // Appliquer récursivement pour les tableaux imbriqués
            if (is_array($value)) {
                $value = self::getCamelizedKeys($value, $prefix);
            }

            // Ajouter l'élément avec la clé camelisée
            $result[$camelizedKey] = $value;
        }

        return $result;
    }

    /**
     * Camelize a option name "days_of_week_disabled" -> "daysOfWeekDisabled".
     */
    public static function camelize(string $name): string
    {
        // $camelizedKey = lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $key))));
        return (string) preg_replace_callback(
            '/_([a-z])/',
            static fn (array $char): string => strtoupper($char[1]),
            $name
        );
    }
}
