<?php

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\NotificationInterface;

/**
 * Evènements sur la liste des notifications de la barre de navigation.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class NotificationsEvent extends BackOfficeEvent
{
    /**
     * Liste des notifications.
     *
     * @var NotificationInterface[]
     */
    protected $notifications = [];

    /**
     * Nombre max d'affichage de notifs dans la barre.
     *
     * @var int
     */
    protected $max = 3;

    /**
     * Nombre total de notifs.
     *
     * @var int
     */
    protected $total = 0;

    /**
     * Route vers une notif.
     *
     * @var string
     */
    protected $route;

    /**
     * Route vers toutes les notifs.
     *
     * @var string
     */
    protected $routeAll;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     *
     * @return NotificationsEvent
     */
    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return 0 === $this->total ? count($this->notifications) : $this->total;
    }

    /**
     * @param int $total
     *
     * @return NotificationsEvent
     */
    public function setTotal(int $total): self
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
     *
     * @return NotificationsEvent
     */
    public function setRoute(string $route): self
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
     *
     * @return NotificationsEvent
     */
    public function setRouteAll(string $routeAll): self
    {
        $this->routeAll = $routeAll;

        return $this;
    }

    /**
     * Retourne les N notifications.
     *
     * @return NotificationInterface[]
     */
    public function getNotifications(): array
    {
        return array_slice($this->notifications, 0, $this->max);
    }

    /**
     * Ajoute un nouvelle notif.
     *
     * @param NotificationInterface $item
     *
     * @return NotificationsEvent
     */
    public function addNotification(NotificationInterface $item): self
    {
        $this->notifications[] = $item;

        return $this;
    }
}
