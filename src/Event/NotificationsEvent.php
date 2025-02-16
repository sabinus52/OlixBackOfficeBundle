<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Event;

use Olix\BackOfficeBundle\Model\NotificationModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @var NotificationModel[]
     */
    protected $notifications = [];

    /**
     * Liste des options.
     *
     * @var array<string,string|int|null>
     */
    protected array $options = [];

    /**
     * @param array<string,mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @param array<string,mixed> $options
     */
    public function setOptions(array $options): self
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        return $this;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        // Nombre max d'affichage de notifications dans la barre.
        $resolver->setDefault('max', 3);
        $resolver->setAllowedTypes('max', 'int');
        $resolver->setAllowedValues('max', static fn ($value) => $value > 1);

        // Route vers toutes les notifications.
        $resolver->setDefault('route', null);
        $resolver->setAllowedTypes('route', ['string', 'null']);
        $resolver->setDefault('route_args', []);
        $resolver->setAllowedTypes('route_args', 'array');

        // Classe CSS de la boite de notification.
        $resolver->setDefault('class', '');
        $resolver->setAllowedTypes('class', 'string');
    }

    public function __call(string $name, mixed $arguments): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        return $this->options[$name];
    }

    public function getTotal(): int
    {
        return count($this->notifications);
    }

    /**
     * Retourne les N notifications.
     *
     * @return NotificationModel[]
     */
    public function getNotifications(): array
    {
        return array_slice($this->notifications, 0, (int) $this->options['max']);
    }

    /**
     * Ajoute un nouvelle notifications.
     */
    public function addNotification(NotificationModel $item): self
    {
        $this->notifications[] = $item;

        return $this;
    }
}
