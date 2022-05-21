<?php

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe d'une notification dans la barre de navigation.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface NotificationInterface
{
    /**
     * @return string
     */
    public function getCode(): ?string;

    /**
     * @return string
     */
    public function getIcon(): string;

    /**
     * @return string
     */
    public function getColor(): ?string;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return string
     */
    public function getInfo(): ?string;
}
