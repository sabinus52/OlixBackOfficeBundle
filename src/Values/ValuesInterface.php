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
 * Interface des listes de valeurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
interface ValuesInterface
{
    public function setValue(int|string $value): void;

    public function getValue(): int|string;

    public function getLabel(): string;

    public function getColor(): string;
}
