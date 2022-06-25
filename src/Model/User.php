<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Classe abstraite de l'entité de la table utilisateurs de connexion.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields="username", message="Ce login est déjà utilisé, merci d'en choisir un autre")
 * @UniqueEntity(fields="email", message="Cet email est déjà utilisé, merci d'en choisir un autre")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    protected const AVATAR_PATH = 'bundles/olixbackoffice/images/avatar/';
    protected const AVATAR_DEFAULT = 'default.png';

    protected const DELAY_ACTIVITY = 5;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=180)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Length(min=2, max=180)
     */
    protected $name;

    /**
     * @var bool
     * @ORM\Column(type="smallint", options={"default": 1})
     */
    protected $enabled = true;

    /**
     * @var DateTime
     * @ORM\Column(name="expiresat", type="date", nullable=true)
     */
    protected $expiresAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $avatar;

    /**
     * @var DateTime
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    protected $lastActivity;

    /**
     * @var array<string> liste des roles
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string Mot de passe hashé
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return User
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        if (null !== $this->expiresAt && $this->expiresAt->getTimestamp() < time()) {
            return true;
        }

        return false;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function setExpiresAt(?DateTimeInterface $date = null): self
    {
        $this->expiresAt = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        if (!$this->avatar) {
            return self::AVATAR_PATH.self::AVATAR_DEFAULT;
        }
        if ('http' === substr($this->avatar, 0, 4)) {
            return $this->avatar;
        }

        return self::AVATAR_PATH.$this->avatar;
    }

    /**
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastLogin(): ?DateTimeInterface
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     *
     * @return User
     */
    public function setLastLogin(?DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @param DateTime $lastActivity
     *
     * @return User
     */
    public function setLastActivity(?DateTimeInterface $lastActivity): self
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastActivity(): ?DateTimeInterface
    {
        return $this->lastActivity;
    }

    /**
     * Indique que l'utilisateur est en activité.
     *
     * @return User
     */
    public function setOnline(): self
    {
        $this->setLastActivity(new DateTime());

        return $this;
    }

    /**
     * Verifie si l'utilisateur est en activité.
     *
     * @param int $minDelay Minutes d'inactivité
     *
     * @return bool
     */
    public function isOnline(int $minDelay = self::DELAY_ACTIVITY)
    {
        $delay = new DateTime();
        $timeDelay = (int) strtotime(sprintf('%s minutes ago', $minDelay));
        $delay->setTimestamp($timeDelay);

        return $this->getLastActivity() > $delay;
    }

    /**
     * @param int $minDelay Minutes d'inactivité
     *
     * @return string
     */
    public function getOnlineBadge(int $minDelay = self::DELAY_ACTIVITY): string
    {
        if ($this->isOnline($minDelay)) {
            return '<span class="badge bg-green">OUI</span>';
        }

        return '<span class="badge bg-red">NON</span>';
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     *
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     *
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
