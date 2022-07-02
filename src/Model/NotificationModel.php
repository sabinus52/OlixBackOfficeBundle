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
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $info;

    /**
     * Constructeur.
     *
     * @param string|null  $code    : Code identifiant de la notif
     * @param array<mixed> $options : Options de la notif
     */
    public function __construct(?string $code = null, array $options = [])
    {
        $this->code = $code;
        $this->icon = $options['icon'] ?? 'fas fa-exclamation-triangle';
        $this->color = $options['color'] ?? null;
        $this->message = $options['message'] ?? '';
        $this->info = $options['info'] ?? null;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return NotificationModel
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return NotificationModel
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return NotificationModel
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * @param string $info
     *
     * @return NotificationModel
     */
    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }
}
