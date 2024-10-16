<?php

declare(strict_types=1);

namespace App\Security;

use App\ReadModel\User\AuthView;
use App\ReadModel\User\UserFetcher;
use Doctrine\DBAL\Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private readonly UserFetcher $users)
    {
    }

    /**
     * @throws Exception
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->loadUser($identifier);
        return self::identityByUser($user);
    }

    /**
     * @throws Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Instances user class ' . \get_class($user));
        }

        return self::identityByUser($this->loadUser($user->getUsername()));
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    /**
     * @throws Exception
     */
    private function loadUser(string $username): AuthView
    {
        if (!$user = $this->users->findForAuth($username)) {
            throw new UserNotFoundException('');
        }
        return $user;
    }

    private static function identityByUser(AuthView $user): UserIdentity
    {
        return new UserIdentity($user->id, $user->email, $user->password_hash, $user->role, $user->status);
    }
}