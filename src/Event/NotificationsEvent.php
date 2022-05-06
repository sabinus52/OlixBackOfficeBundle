<?php
/**
 * Evènements sur la liste des notifications de la barre de navigation
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\NotificationInterface;


class NotificationsEvent extends BackOfficeEvent
{

    /**
     * Liste des notifications
     * 
     * @var NotificationInterface[]
     */
    protected $notifications = [];

    /**
     * Nombre max d'affichage de notifs dans la barre
     * 
     * @var integer
     */
    protected $max = 3;

    /**
     * Nombre total de notifs
     * 
     * @var integer
     */
    protected $total = 0;

    /**
     * Route vers une notif
     * 
     * @var string
     */
    protected $route;

    /**
     * Route vers toutes les notifs
     * 
     * @vaar string
     */
    protected $routeAll;


    /**
     * Constructor
     */
    public function __construct()
    {
    }


    /**
     * @return integer
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param integer $max
     * @return NotificationsEvent
     */
    public function setMax(int $max): NotificationsEvent
    {
        $this->max = $max;
        return $this;
    }


    /**
     * @return integer
     */
    public function getTotal(): int
    {
        return $this->total === 0 ? count($this->notifications) : $this->total;
    }

    /**
     * @param integer $total
     * @return NotificationsEvent
     */
    public function setTotal(int $total): NotificationsEvent
    {
        $this->total = $total;
        return $this;
    }


    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return NotificationsEvent
     */
    public function setRoute(string $route): NotificationsEvent
    {
        $this->route = $route;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getRouteAll(): ?string
    {
        return $this->routeAll;
    }

    /**
     * @param string $routeAll
     * @return NotificationsEvent
     */
    public function setRouteAll(string $routeAll): NotificationsEvent
    {
        $this->routeAll = $routeAll;
        return $this;
    }


    /**
     * Retourne les N notifications
     * 
     * @return NotificationInterface[]
     */
    public function getNotifications(): array
    {
        return array_slice($this->notifications, 0, $this->max);
    }


    /**
     * Ajoute un nouvelle notif
     * 
     * @param NotificationInterface $item
     * @return NotificationsEvent
     */
    public function addNotification(NotificationInterface $item): NotificationsEvent
    {
        $this->notifications[] = $item;

        return $this;
    }

}