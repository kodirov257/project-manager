<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'user_users')]
#[ORM\UniqueConstraint(columns: ['email'])]
#[ORM\UniqueConstraint(columns: ['reset_token_token'])]
class User
{
    private const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    #[ORM\Column(type: 'user_user_id')]
    #[ORM\Id]
    private Id $id;
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $date;
    #[ORM\Column(type: 'user_user_email', nullable: true)]
    private ?Email $email;
    #[ORM\Column(name: 'password_hash', type: 'string', nullable: true)]
    private ?string $passwordHash;
    #[ORM\Column(name: 'confirm_token', type: 'string', nullable: true)]
    private ?string $confirmToken;
    #[ORM\Embedded(class: 'ResetToken', columnPrefix: 'reset_token_')]
    private ?ResetToken $resetToken;
    #[ORM\Column(type: 'string', length: 16)]
    private string $status;
    #[ORM\Column(type: 'user_user_role', length: 16)]
    private Role $role;
    /**
     * @var Network[]|ArrayCollection|Collection
     */
    #[ORM\OneToMany(targetEntity: 'Network', mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private array|Collection|ArrayCollection $networks;

    private function __construct(Id $id, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function signUpByEmail(Id $id, \DateTimeImmutable $date, Email $email, string $hash, string $token): self
    {
        $user = new self($id, $date);

        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;

        return $user;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public static function signUpByNetwork(Id $id, \DateTimeImmutable $date, string $network, string $identity): self
    {
        $user = new self($id, $date);

        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;

        return $user;
    }

    public function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $expires): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($expires)) {
            throw new \DomainException('Resetting is already requested.');
        }
        $this->resetToken = $token;
    }

    public function resetPassword(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    #[ORM\PostLoad]
    public function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}