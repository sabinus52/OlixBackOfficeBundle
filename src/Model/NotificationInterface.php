<?php
/**
 * Interface de la classe d'une notification dans la barre de navigation
 * 
 * @author Olivier <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Model;


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