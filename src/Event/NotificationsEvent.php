<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\NotificationInterface;

/**
 * Évènements sur la liste des notifications de la barre de navigation.
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
     * Nombre max d'affichage de notifications dans la barre.
     */
    protected int $max = 3;

    /**
     * Nombre total de notifications.
     */
    protected int $total = 0;

    /**
     * Route vers une notifications.
     */
    protected string $route;

    /**
     * Route vers toutes les notifications.
     */
    protected string $routeAll;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getTotal(): int
    {
        return 0 === $this->total ? count($this->notifications) : $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getRouteAll(): ?string
    {
        return $this->routeAll;
    }

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
     * Ajoute un nouvelle notifications.
     */
    public function addNotification(NotificationInterface $item): self
    {
        $this->notifications[] = $item;

        return $this;
    }
}
