<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class UserFetcher
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (*)')
                ->from('user_users')
                ->where('reset_token_token = ?')
                ->setParameter(0, $token)
                ->executeQuery()->fetchOne() > 0;
    }

    /**
     * @throws Exception
     */
    public function findForAuth(string $email): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id', 'email', 'password_hash', 'role', 'status')
            ->from('user_users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->executeQuery();

        $result = $stmt->fetchAssociative();

        return $result
            ? new AuthView($result['id'], $result['email'], $result['password_hash'], $result['role'], $result['status'])
            : null;
    }
}