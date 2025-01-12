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
    public function setKey(int $key): void;

    public function getKey(): int;

    public function getLabel(): string;

    public function getField(string $fieldName): string;

    /**
     * @return array<string,string>
     */
    public function getValue(): array;
}
