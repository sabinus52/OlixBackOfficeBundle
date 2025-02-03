<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\ValuesList;

/**
 * Classe abstraites des listes de valeurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 *
 * @deprecated 1.2 Utiliser les valeurs de la classe Enum
 *
 * template T of array<int,array<string,string>>
 */
abstract class ValuesListAbstract implements \Stringable, ValuesListInterface
{
    /**
     * Liste des valeurs.
     *
     * @var array<int,array<string,string>>
     */
    protected static array $values = [];

    public function __construct(protected int $key)
    {
        if (!static::isExists($this->key)) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'existe pas dans la liste de valeurs.', $this->key));
        }
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }

    /**
     * Retourne la valeur associée à la clé de la valeur donné.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function getKey(): int
    {
        return $this->key;
    }

    /**
     * Affecte la valeur associée à la clé donné.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function setKey(int $key): void
    {
        if (!static::isExists($this->key)) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'existe pas dans la liste de valeurs.', $this->key));
        }
        $this->key = $key;
    }

    /**
     * Vérifie si la clé existe dans la liste des valeurs.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public static function isExists(int $key): bool
    {
        return array_key_exists($key, static::$values);
    }

    /**
     * Retourne le label associé à la valeur courante.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function getLabel(): string
    {
        if (!array_key_exists('label', static::$values[$this->key])) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'a pas de champs "label" dans la liste de valeurs.', $this->key));
        }

        return static::$values[$this->key]['label'];
    }

    /**
     * Retrouve la valeur d'un champ "$fieldName" de la valeur courante.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function getField(string $fieldName): string
    {
        if (!array_key_exists($fieldName, static::$values[$this->key])) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'a pas de champs "%s" dans la liste de valeurs.', $this->key, $fieldName));
        }

        return static::$values[$this->key][$fieldName];
    }

    /**
     * Retourne la valeur associée à la valeur courante.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @return array<string,string>
     */
    public function getValue(): array
    {
        return static::$values[$this->key];
    }

    /**
     * Retourne la liste des valeurs.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @return array<int,array<string,string>>
     */
    public static function getValues(): array
    {
        return static::$values;
    }

    /**
     * Retourne la liste pour le ChoiceType des formulaires.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @return static[]
     */
    public static function getChoices(): array
    {
        return self::getChoicesByList(null);
    }

    /**
     * Retourne la liste pour le ChoiceType des formulaires.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @param array<int>|null $list Liste des valeurs à retourner
     *
     * @return static[]
     */
    public static function getChoicesByList(?array $list = null): array
    {
        $result = [];

        // Retourne les clés de la liste si elle est précisée ou tous si null
        $list ??= array_keys(static::$values);

        foreach ($list as $key) {
            $result[] = new static($key); // @phpstan-ignore new.static
        }

        return $result;
    }

    /**
     * Retourne la liste pour le filtre dans les Datatable.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @return array<int|string,string>
     */
    public static function getFilters(string $field = 'label'): array
    {
        $result = [];

        self::checkIsFieldExists($field);

        foreach (static::$values as $key => $value) {
            $result[$key] = $value[$field];
        }

        return $result;
    }

    /**
     * Retourne l'objet en recherchant la valeur en fonction du champ donné.
     *
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @param array<string,string> $field Champs à rechercher et sa valeur [ $fieldName => $value ]
     */
    public static function searchObjectByField(array $field): ?static
    {
        $fieldName = key($field);
        $searchValue = $field[$fieldName];

        // Extrait la valeur de la colonne "$fieldName" de la liste de valeurs
        $colonne = array_map(static fn ($ligne): string => $ligne[$fieldName], static::$values);
        $index = array_search($searchValue, $colonne, false);
        if (false === $index) {
            return null;
        }

        return new static($index); // @phpstan-ignore new.static
    }

    /**
     * Vérifie que la liste de valeurs contient au moins une clé "label".
     */
    protected static function checkIsFieldExists(string $field = 'label'): void
    {
        /** @var array<string,string> $firstValue */
        $firstValue = current(static::$values);
        if (!array_key_exists($field, $firstValue)) {
            throw new \RuntimeException('La liste de valeurs doit contenir au moins une clé "label".');
        }
    }
}
