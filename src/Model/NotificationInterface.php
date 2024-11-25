<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe d'une notification dans la barre de navigation.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface NotificationInterface
{
    public function getCode(): ?string;

    public function getIcon(): string;

    public function getColor(): ?string;

    public function getMessage(): string;

    public function getInfo(): ?string;
}
