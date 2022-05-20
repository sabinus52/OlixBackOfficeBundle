<?php
/**
 * Interface pour la gestion des utilisateurs
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Security;


interface UserManagerInterface
{

    /**
     * Retourne le nom de la classe qui sera défini lors de la surcharge
     * 
     * @return string
     */
    public function getClass(): string;

}