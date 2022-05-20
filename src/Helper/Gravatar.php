<?php

namespace Olix\BackOfficeBundle\Helper;

use InvalidArgumentException;

/**
 * Classe pour l'avatar de l'utilisateur en utilisant le service "Gravatar"
 *
 * @package    Olix
 * @subpackage BackOfficeBundle
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class Gravatar
{
    /**
     * Constantes des URL des avatars
     */
    protected const HTTP_URL = 'http://www.gravatar.com/avatar/';
    protected const HTTPS_URL = 'https://secure.gravatar.com/avatar/';


    /**
     * Taille de l'avatar
     * @var integer
     */
    protected $size = 128;

    /**
     * Avatar par defaut URL externe ou ('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro')
     * @var string
     */
    protected $defaultImage = 'monsterid';

    /**
     * Rating par defaut (Valeur possible 'g', 'pg', 'r', 'x')
     * @var string
     */
    protected $rating = 'g';

    /**
     * Si utilisation du SSL
     * @var boolean
     */
    protected $secureUrl = true;



    /**
     * Retourne la taille courante de l'avatar
     *
     * @return integer
     */
    public function getSize(): int
    {
        return $this->size;
    }


    /**
     * Affecte la taille de l'image
     *
     * @param integer $size
     * @return Gravatar
     */
    public function setSize(int $size): self
    {
        if ($size > 512 || $size < 0) {
            throw new InvalidArgumentException('Avatar size must be within 0 pixels and 512 pixels');
        }
        $this->size = $size;
        return $this;
    }


    /**
     * Retourne l'image par défaut
     *
     * @return string
     */
    public function getDefaultImage(): string
    {
        return $this->defaultImage;
    }


    /**
     * Affecte l'image par défaut
     *
     * @param string $image
     * @return Gravatar
     */
    public function setDefaultImage(string $image): self
    {
        $validDefaults = array('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro');

        // Verifie la bonne url
        if (! filter_var($image, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('The default image specified is not a recognized gravatar "default" and is not a valid URL');
        }

        $imgLower = strtolower($image);
        $this->defaultImage = (in_array($imgLower, $validDefaults)) ? $imgLower : rawurlencode($image);

        return $this;
    }


    /**
     * Retroune le rating
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }


    /**
     * Affecte le rating
     *
     * @param string $rating
     * @return Gravatar
     */
    public function setRating(string $rating): self
    {
        $rating = strtolower($rating);
        $validRatings = array('g', 'pg', 'r', 'x');
        if (! in_array($rating, $validRatings)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid rating "%s" specified, only "g", "pg", "r", or "x" are allowed to be used.',
                $rating
            ));
        }
        $this->rating = $rating;
        return $this;
    }


    /**
     * Verifie si on utilise le SSL
     *
     * @return boolean
     */
    public function usingSecureImages(): bool
    {
        return $this->secureUrl;
    }


    /**
     * Active le protocole SSL
     *
     * @return Gravatar
     */
    public function enableSecureImages(): self
    {
        $this->secureUrl = true;
        return $this;
    }


    /**
     * Desactive le protocole SSL
     *
     * @return Gravatar
     */
    public function disableSecureImages(): self
    {
        $this->secureUrl = false;
        return $this;
    }


    /**
     * @param string $email
     * @see Gravatar::buildURL()
     */
    public function get(string $email): string
    {
        return $this->buildURL($email);
    }


    /**
     * Construit l'url de l'avatar à partir de l'émail
     *
     * @param string $email
     * @return string
     */
    protected function buildURL(string $email): string
    {
        $url = ( $this->usingSecureImages() ) ? static::HTTPS_URL : static::HTTP_URL;
        $url .= (! empty($email)) ? $this->getEmailHash($email) : str_repeat('0', 32);

        return $url . $this->getGravatarParams($email);
    }


    /**
     * Construit et retourne les paramètres pour l'url de l'avatar
     *
     * @param string $email
     * @return string
     */
    protected function getGravatarParams($email)
    {
        $params = array();
        $params[] = 's=' . $this->getSize();
        $params[] = 'r=' . $this->getRating();
        if ($this->getDefaultImage()) {
            $params[] = 'd=' . $this->getDefaultImage();
        }
        if (empty($email)) {
            $params[] = 'f=y'; // Force l'image par defaut
        }
        return '?' . implode('&', $params);
    }


    /**
     * Retourne l'email avec hash
     *
     * @param string $email
     * @return string
     */
    protected function getEmailHash(string $email): string
    {
        // Using md5 as per gravatar docs.
        return hash('md5', strtolower(trim($email)));
    }
}
