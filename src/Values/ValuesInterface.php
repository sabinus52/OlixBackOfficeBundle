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
 * Interface des listes de valeurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
interface ValuesInterface
{
    /**
     * @return static[]
     */
    public static function getChoices(): array;

    /**
     * @return array<mixed>
     */
    public static function getFilters(): array;

    public function setValue(int $value): void;

    public function getValue(): int;

    public function getLabel(): string;

    public function getColor(): string;
}
