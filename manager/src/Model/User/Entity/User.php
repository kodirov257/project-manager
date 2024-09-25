<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

class User
{
    private string $email;
    private string $passwordHash;

    public function __construct(string $email, string $hash)
    {
        $this->email = $email;
        $this->passwordHash = $hash;
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