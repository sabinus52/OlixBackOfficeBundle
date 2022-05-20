<?php

namespace Olix\BackOfficeBundle\Security;

/**
 * Interface pour la gestion des utilisateurs
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface UserManagerInterface
{
    /**
     * Retourne le nom de la classe qui sera défini lors de la surcharge
     *
     * @return string
     */
    public function getClass(): string;
}
