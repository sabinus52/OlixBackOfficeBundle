<?php

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe d'une notification dans la barre de navigation
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
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
