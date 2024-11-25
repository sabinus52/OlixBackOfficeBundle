<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Helper;

/**
 * Classe pour l'avatar de l'utilisateur en utilisant le service "Gravatar".
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class Gravatar
{
    /**
     * Constantes des URL des avatars.
     */
    protected const HTTP_URL = 'http://www.gravatar.com/avatar/';

    protected const HTTPS_URL = 'https://secure.gravatar.com/avatar/';

    /**
     * Taille de l'avatar.
     */
    protected int $size = 128;

    /**
     * Avatar par défaut URL externe ou ('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro').
     */
    protected string $defaultImage = 'monsterid';

    /**
     * Rating par défaut (Valeur possible 'g', 'pg', 'r', 'x').
     */
    protected string $rating = 'g';

    /**
     * Si utilisation du SSL.
     */
    protected bool $secureUrl = true;

    /**
     * Retourne la taille courante de l'avatar.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Affecte la taille de l'image.
     */
    public function setSize(int $size): self
    {
        if ($size > 512 || $size < 0) {
            throw new \InvalidArgumentException('Avatar size must be within 0 pixels and 512 pixels');
        }

        $this->size = $size;

        return $this;
    }

    /**
     * Retourne l'image par défaut.
     */
    public function getDefaultImage(): string
    {
        return $this->defaultImage;
    }

    /**
     * Affecte l'image par défaut.
     */
    public function setDefaultImage(string $image): self
    {
        $validDefaults = ['404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro'];

        // Vérifie la bonne url
        if (!filter_var($image, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('The default image specified is not a recognized gravatar "default" and is not a valid URL');
        }

        $imgLower = strtolower($image);
        $this->defaultImage = (in_array($imgLower, $validDefaults, true)) ? $imgLower : rawurlencode($image);

        return $this;
    }

    /**
     * Retourne le rating.
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * Affecte le rating.
     */
    public function setRating(string $rating): self
    {
        $rating = strtolower($rating);
        $validRatings = ['g', 'pg', 'r', 'x'];
        if (!in_array($rating, $validRatings, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid rating "%s" specified, only "g", "pg", "r", or "x" are allowed to be used.', $rating));
        }

        $this->rating = $rating;

        return $this;
    }

    /**
     * Vérifie si on utilise le SSL.
     */
    public function usingSecureImages(): bool
    {
        return $this->secureUrl;
    }

    /**
     * Active le protocole SSL.
     */
    public function enableSecureImages(): self
    {
        $this->secureUrl = true;

        return $this;
    }

    /**
     * Désactive le protocole SSL.
     */
    public function disableSecureImages(): self
    {
        $this->secureUrl = false;

        return $this;
    }

    /**
     * @see Gravatar::buildURL()
     */
    public function get(string $email): string
    {
        return $this->buildURL($email);
    }

    /**
     * Construit l'url de l'avatar à partir de l'émail.
     */
    protected function buildURL(string $email): string
    {
        $url = ($this->usingSecureImages()) ? static::HTTPS_URL : static::HTTP_URL;
        $url .= (empty($email)) ? str_repeat('0', 32) : $this->getEmailHash($email);

        return $url.$this->getGravatarParams($email);
    }

    /**
     * Construit et retourne les paramètres pour l'url de l'avatar.
     */
    protected function getGravatarParams(string $email): string
    {
        $params = [];
        $params[] = 's='.$this->getSize();
        $params[] = 'r='.$this->getRating();
        if ('' !== $this->getDefaultImage()) {
            $params[] = 'd='.$this->getDefaultImage();
        }

        if (empty($email)) {
            $params[] = 'f=y'; // Force l'image par défaut
        }

        return '?'.implode('&', $params);
    }

    /**
     * Retourne l'email avec hash.
     */
    protected function getEmailHash(string $email): string
    {
        // Using md5 as per gravatar docs.
        return hash('md5', strtolower(trim($email)));
    }
}
