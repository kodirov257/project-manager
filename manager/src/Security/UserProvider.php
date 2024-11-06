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
        return self::identityByUser($user, $identifier);
    }

    /**
     * @throws Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Instances user class ' . \get_class($user));
        }

        return self::identityByUser($this->loadUser($user->getUsername()), $user->getUsername());
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
        $chunks = explode(':', $username);

        if (\count($chunks) === 2 && $user = $this->users->findForAuthByNetwork($chunks[0], $chunks[1])) {
            return $user;
        }

        if ($user = $this->users->findForAuthByEmail($username)) {
            return $user;
        }

        throw new UserNotFoundException('');
    }

    private static function identityByUser(AuthView $user, string $username): UserIdentity
    {
        return new UserIdentity($user->id, $user->email ?: $username, $user->password_hash ?: '', $user->role, $user->status);
    }
}