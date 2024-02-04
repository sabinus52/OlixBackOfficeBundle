<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

/**
 * Classe d'une notification dans la barre de navigation.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class NotificationModel implements NotificationInterface
{
    protected string $icon;

    protected ?string $color;

    protected string $message;

    protected ?string $info;

    /**
     * Constructeur.
     *
     * @param ?string      $code    : Code identifiant de la notif
     * @param array<mixed> $options : Options de la notif
     */
    public function __construct(protected ?string $code = null, array $options = [])
    {
        $this->icon = $options['icon'] ?? 'fas fa-exclamation-triangle';
        $this->color = $options['color'] ?? null;
        $this->message = $options['message'] ?? '';
        $this->info = $options['info'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }
}
