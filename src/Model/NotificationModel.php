<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

use Olix\BackOfficeBundle\Enum\ColorBS;
use Olix\BackOfficeBundle\Enum\ColorCSS;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Classe d'une notification dans la barre de navigation.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class NotificationModel
{
    /**
     * Liste des options de la notification.
     *
     * @var array<string,string|null>
     */
    protected array $options = [];

    /**
     * @param array<mixed> $options : Options de la notification
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        // Icône de la notification
        $resolver->setDefault('icon', 'fas fa-exclamation-triangle');
        $resolver->setAllowedTypes('icon', ['string']);

        // Couleur de l'icône de la notification
        $resolver->setDefault('color', null);
        $resolver->setAllowedTypes('color', ['string', 'null']);
        $resolver->setAllowedValues('color', [null] + array_merge(ColorBS::values(), ColorCSS::values()));

        // Message de la notification
        $resolver->setDefault('message', '');
        $resolver->setAllowedTypes('message', ['string']);

        // Info complémentaire de la notification
        $resolver->setDefault('info', null);
        $resolver->setAllowedTypes('info', ['string', 'null']);

        // Route vers une notification.
        $resolver->setDefault('route', null);
        $resolver->setAllowedTypes('route', ['string', 'null']);
        $resolver->setDefault('route_args', []);
        $resolver->setAllowedTypes('route_args', 'array');
    }

    public function __call(string $name, mixed $arguments): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        return $this->options[$name] ?? null;
    }
}
