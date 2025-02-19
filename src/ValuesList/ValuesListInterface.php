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
 * Interface ValuesListInterface.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
interface ValuesListInterface
{
    /**
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function setKey(int $key): void;

    /**
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function getKey(): int;

    /**
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function getLabel(): string;

    /**
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     */
    public function getField(string $fieldName): string;

    /**
     * @deprecated 1.2 Utiliser les valeurs de la classe Enum
     *
     * @return array<string,string>
     */
    public function getValue(): array;
}
