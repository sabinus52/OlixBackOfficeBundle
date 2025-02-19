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
     * Retourne la valeur d'un tableau à partir d'une chaîne de clés.
     * Exemple: getNestedValueFromArray(['foo' => ['bar' => 'baz']], 'foo.bar') retourne 'baz'.
     *
     * Retourne la valeur par défaut si la clé n'existe pas.
     *
     * @param array<string,mixed> $array
     */
    public static function getNestedValueFromArray(array $array, string $keyPath, mixed $default = null): mixed
    {
        $keys = explode('.', $keyPath);
        $result = $array;

        foreach ($keys as $key) {
            if (!is_array($result)) {
                return $default; // Retourne la valeur par défaut
            }
            if (!array_key_exists($key, $result)) {
                return $default; // Retourne la valeur par défaut
            }
            /** @var array<string,mixed> $result */
            $result = $result[$key];
        }

        return $result;
    }

    /**
     * Fusionne une valeur dans un tableau à partir d'une clé de chemin.
     *
     * @param array<string,mixed> $baseArray
     */
    public static function mergeNestedValueInArray(array &$baseArray, string $keyPath, mixed $value): void
    {
        $keys = explode('.', $keyPath);
        $result = &$baseArray;

        foreach ($keys as $key) {
            if (!isset($result[$key]) || !is_array($result[$key])) {
                $result[$key] = [];
            }
            $result = &$result[$key];
        }

        $result = $value; // Assigner la valeur finale
    }

    /**
     * Camelize les clés d'un tableau uniquement pour les clés qui commencent par le préfixe.
     *
     * @param array<mixed> $array
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
                continue;
            }

            // Appliquer récursivement pour les tableaux imbriqués
            /*if (is_array($value)) {
                $value = self::getCamelizedKeys($value, $prefix);
            }*/

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
