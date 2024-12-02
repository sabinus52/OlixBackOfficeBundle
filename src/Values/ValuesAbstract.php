<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Values;

/**
 * Classe abstraites des listes de valeurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 *
 * template T of array<int,array<string,string>>|array<string,array<string,string>>
 */
abstract class ValuesAbstract implements \Stringable
{
    /**
     * Liste des valeurs.
     *
     * @var array<int,array<string,string>>|array<string,array<string,string>>
     */
    protected static $values = [];

    public function __construct(protected int|string $value)
    {
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function setValue(int|string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        if (!isset(static::$values[$this->value])) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'existe pas dans la liste de valeurs.', $this->value));
        }
        if (!isset(static::$values[$this->value]['label'])) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'a pas de champs "label" dans la liste de valeurs.', $this->value));
        }

        return static::$values[$this->value]['label'];
    }

    public function getColor(): string
    {
        if (!isset(static::$values[$this->value])) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'existe pas dans la liste de valeurs.', $this->value));
        }
        if (!isset(static::$values[$this->value]['color'])) {
            throw new \RuntimeException(sprintf('La valeur "%s" n\'a pas de champs "color" dans la liste de valeurs.', $this->value));
        }

        return static::$values[$this->value]['color'];
    }

    /**
     * Retourne la liste pour le ChoiceType des formulaires.
     *
     * @return static[]
     */
    public static function getChoices(): array
    {
        $result = [];

        foreach (array_keys(static::$values) as $value) {
            $result[] = new static($value); // @phpstan-ignore new.static
        }

        return $result;
    }

    /**
     * Retourne la liste pour le filtre dans les Datatable.
     *
     * @return array<int|string,string>
     */
    public static function getFilters(): array
    {
        $result = [];

        self::checkIsFieldExists();

        foreach (static::$values as $key => $value) {
            $result[$key] = $value['label'];
        }

        return $result;
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
