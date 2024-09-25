<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

class User
{
    private string $id;
    private \DateTimeImmutable $date;
    private string $email;
    private string $passwordHash;

    public function __construct(string $id, \DateTimeImmutable $date, string $email, string $hash)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $hash;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}