<?php
/**
 * Classe d'une notification dans la barre de navigation
 * 
 * @author Olivier <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Model;


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
     * Constructeur
     * 
     * @param string|null $code : Code identifiant de la notif
     * @param array $options : Options de la notif
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
     * @return NotificationInterface
     */
    public function setIcon(string $icon): NotificationInterface
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
     * @return NotificationInterface
     */
    public function setColor(?string $color): NotificationInterface
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
     * @return NotificationInterface
     */
    public function setMessage(string $message): NotificationInterface
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
     * @return NotificationInterface
     */
    public function setInfo(?string $info): NotificationInterface
    {
        $this->info = $info;
        return $this;
    }

}