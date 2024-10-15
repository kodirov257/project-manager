<?php

declare(strict_types=1);

namespace App\Security;

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
        $user = $this->users->findForAuth($identifier);

        if (!$user) {
            throw new UserNotFoundException('');
        }

        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password_hash,
            $user->role,
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Instances user class ' . \get_class($user));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }
}