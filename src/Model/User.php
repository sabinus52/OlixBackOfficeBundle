<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Classe abstraite de l'entité de la table utilisateurs de connexion.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
#[ORM\MappedSuperclass]
#[UniqueEntity(fields: 'username', message: "Ce login est déjà utilisé, merci d'en choisir un autre")]
#[UniqueEntity(fields: 'email', message: "Cet email est déjà utilisé, merci d'en choisir un autre")]
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    protected const AVATAR_PATH = 'bundles/olixbackoffice/images/avatar/';

    protected const AVATAR_DEFAULT = 'default.png';

    protected const DELAY_ACTIVITY = 5;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected int $id;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 180)]
    protected string $username;

    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    #[Assert\Email]
    protected ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    #[Assert\Length(min: 2, max: 180)]
    protected ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    protected bool $enabled = true;

    #[ORM\Column(name: 'expiresat', type: Types::DATE_MUTABLE, nullable: true)]
    protected ?\DateTime $expiresAt = null;

    #[ORM\Column(type: Types::STRING, length: 250, nullable: true)]
    protected ?string $avatar = null;

    #[ORM\Column(name: 'last_login', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTime $lastLogin = null;

    #[ORM\Column(name: 'last_activity', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTime $lastActivity = null;

    /**
     * @var array<string> liste des roles
     */
    #[ORM\Column(type: Types::JSON)]
    protected $roles = [];

    #[ORM\Column(type: Types::STRING)]
    protected string $password; // Mot de passe hashé

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->username;
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
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne le badge du statut en cours de l'utilisateur.
     */
    public function getStateBadge(): string
    {
        if (!$this->isEnabled()) {
            return '<span class="badge bg-red">DISABLED</span>';
        }

        if ($this->isExpired()) {
            return '<span class="badge bg-red">EXPIRED</span>';
        }

        if ($this->getExpiresAt() instanceof \DateTime) {
            return '<span class="badge bg-orange">Expires at '.$this->getExpiresAt()->format('d/m/Y').'</span>';
        }

        return '<span class="badge bg-green">ACTIVE</span>';
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt instanceof \DateTime && $this->expiresAt->getTimestamp() < time();
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTime $date = null): self
    {
        $this->expiresAt = $date;

        return $this;
    }

    /**
     * @param string $prefix : pour ajouter un préfix dans l'URL comme un '/'
     */
    public function getAvatar(string $prefix = ''): ?string
    {
        if (null === $this->avatar || '' === $this->avatar || '0' === $this->avatar) {
            return $prefix.self::AVATAR_PATH.self::AVATAR_DEFAULT;
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        return $prefix.self::AVATAR_PATH.$this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Retourne le temps écoulé depuis la dernière connexion.
     */
    public function getIntervalLastLogin(): string
    {
        if (!$this->lastLogin instanceof \DateTime) {
            return '';
        }

        $now = new \DateTime();
        $interval = $now->diff($this->lastLogin);
        if (1 === $interval->days) {
            return $interval->format('%a jour');
        }

        if ($interval->days > 1) {
            return $interval->format('%a jours');
        }

        if (1 === $interval->h) {
            return $interval->format('%h heure');
        }

        if ($interval->h > 1) {
            return $interval->format('%h heures');
        }

        if (1 === $interval->i) {
            return $interval->format('%i minute');
        }

        return $interval->format('%i minutes');
    }

    public function setLastActivity(?\DateTime $lastActivity): self
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    public function getLastActivity(): ?\DateTime
    {
        return $this->lastActivity;
    }

    /**
     * Indique que l'utilisateur est en activité.
     */
    public function setOnline(): self
    {
        $this->setLastActivity(new \DateTime());

        return $this;
    }

    /**
     * Verifie si l'utilisateur est en activité.
     *
     * @param int $minDelay Minutes d'inactivité
     */
    public function isOnline(int $minDelay = self::DELAY_ACTIVITY): bool
    {
        $delay = new \DateTime();
        $timeDelay = (int) strtotime(sprintf('%s minutes ago', $minDelay));
        $delay->setTimestamp($timeDelay);

        return $this->getLastActivity() > $delay;
    }

    /**
     * @param int $minDelay Minutes d'inactivité
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
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

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
