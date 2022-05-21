<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Security;

/**
 * Interface pour la gestion des utilisateurs.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface UserManagerInterface
{
    /**
     * Retourne le nom de la classe qui sera défini lors de la surcharge.
     *
     * @return string
     */
    public function getClass(): string;
}
