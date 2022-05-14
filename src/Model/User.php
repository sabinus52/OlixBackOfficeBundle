<?php
/**
 * Classe abstraite de l'entité de la table utilisateurs de connexion
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\MappedSuperclass
 */
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    const AVATAR_PATH = 'bundles/olixbackoffice/images/avatar/';
    const AVATAR_DEFAULT = 'default.png';

    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $name;

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
     * @var array liste des roles
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
     * @var string $email
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
     * @var string $name
     * @return User
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        if ( ! $this->avatar ) {
            return self::AVATAR_PATH . self::AVATAR_DEFAULT;
        } elseif ( substr($this->avatar, 0, 4) == 'http' ) {
            return $this->avatar;
        } else {
            return self::AVATAR_PATH . $this->avatar;
        }
    }

    /**
     * @var string|null $avatar
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
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    /**
     * @var DateTime $lastLogin
     * @return User
     */
    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }


    /**
     * @see UserInterface
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @var array $roles
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @var string $password
     * @return string
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
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}