<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Values;

/**
 * Classe abstraites des listes de valeurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
abstract class ValuesAbstract implements ValuesInterface, \Stringable
{
    /**
     * Liste des valeurs.
     *
     * @var array<mixed>
     */
    protected static $values = [];

    /**
     * Retourne la liste pour le ChoiceType des formulaires.
     *
     * @return static[]
     */
    public static function getChoices(): array
    {
        $result = [];

        foreach (array_keys(static::$values) as $value) {
            $result[] = new static($value); /** @phpstan-ignore-line */
        }

        return $result;
    }

    /**
     * Retourne la liste pour le filtre dans les Datatables.
     *
     * @return array<string>
     */
    public static function getFilters(): array
    {
        $result = [];

        foreach (static::$values as $key => $value) {
            if (is_array($value) && array_key_exists('label', $value)) {
                $result[$key] = $value['label'];
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function __construct(protected int $value)
    {
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        if (is_array(static::$values[$this->value])) {
            return static::$values[$this->value]['label'];
        }

        return static::$values[$this->value];
    }

    public function getColor(): string
    {
        return static::$values[$this->value]['color'];
    }
}
