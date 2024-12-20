<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\ReadModel\User\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class UserFetcher
{
    public function __construct(private readonly Connection $connection, private readonly PaginatorInterface $paginator)
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
    public function findForAuthByEmail(string $email): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'TRIM(CONCAT(first_name, \' \', last_name)) AS name',
                'role',
                'status',
            )
            ->from('user_users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->executeQuery();

        $result = $stmt->fetchAssociative();

        return $result
            ? new AuthView($result['id'], $result['email'], $result['name'], $result['password_hash'], $result['role'], $result['status'])
            : null;
    }

    /**
     * @throws Exception
     */
    public function findForAuthByNetwork(string $network, string $identity): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'u.id',
                'u.email',
                'u.password_hash',
                'TRIM(CONCAT(u.first_name, \' \', u.last_name)) AS name',
                'u.role',
                'u.status',
            )
            ->from('user_users', 'u')
            ->innerJoin('u', 'user_user_networks', 'n', 'n.user_id = u.id')
            ->where('n.network = ? AND n.identity = ?')
            ->setParameter(0, $network)
            ->setParameter(1, $identity)
            ->executeQuery();

        $result = $stmt->fetchAssociative();

        return $result
            ? new AuthView($result['id'], $result['email'], $result['name'], $result['password_hash'], $result['role'], $result['status'])
            : null;
    }

    /**
     * @throws Exception
     */
    public function findByEmail(string $email): ?ShortView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id', 'email', 'role', 'status')
            ->from('user_users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->executeQuery();

        $result = $stmt->fetchAssociative();

        return $result
            ? new ShortView($result['id'], $result['email'], $result['role'], $result['status'])
            : null;
    }

    /**
     * @throws Exception
     */
    public function findBySignUpConfirmToken(string $token): ?ShortView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id', 'email', 'role', 'status')
            ->from('user_users')
            ->where('confirm_token = ?')
            ->setParameter(0, $token)
            ->executeQuery();

        $result = $stmt->fetchAssociative();

        return $result
            ? new ShortView($result['id'], $result['email'], $result['role'], $result['status'])
            : null;
    }

    public function findDetail(string $id): ?DetailView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id', 'date', 'first_name', 'last_name', 'email', 'role', 'status')
            ->from('user_users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeQuery();

        $result = $stmt->fetchAssociative();

        if (!$result) {
            return null;
        }
        $view = new DetailView(
            $result['id'],
            new \DateTimeImmutable($result['date']),
            $result['first_name'],
            $result['last_name'],
            $result['email'],
            $result['role'],
            $result['status'],
        );

        $stmt = $this->connection->createQueryBuilder()
            ->select('network', 'identity')
            ->from('user_user_networks')
            ->where('user_id = ?')
            ->setParameter(0, $id)
            ->executeQuery();

        $result = $stmt->fetchAllAssociative();
        foreach ($result as $network) {
            $view->networks[] = new NetworkView($network['network'], $network['identity']);
        }

        return $view;
    }

    public function getDetail(string $id): DetailView
    {
        if (!$detail = $this->findDetail($id)) {
            throw new \LogicException('User is not found.');
        }
        return $detail;
    }

    /**
     * @throws Exception
     */
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
                'TRIM(CONCAT(first_name, \' \', last_name)) AS name',
                'email',
                'role',
                'status',
            )
            ->from('user_users');

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(CONCAT(first_name, \' \', last_name))', ':name'));
            $qb->setParameter('name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->email) {
            $qb->andWhere($qb->expr()->like('LOWER(email)', ':email'));
            $qb->setParameter('email', '%' . mb_strtolower($filter->email) . '%');
        }

        if ($filter->status) {
            $qb->andWhere('status = :status');
            $qb->setParameter('status', $filter->status);
        }

        if ($filter->role) {
            $qb->andWhere('role = :role');
            $qb->setParameter('role', $filter->role);
        }

        if (!\in_array($sort, ['date', 'email', 'name', 'role', 'status'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }
}