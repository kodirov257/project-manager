<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

class User
{
    private string $id;
    private string $email;
    private string $passwordHash;

    public function __construct(string $id, string $email, string $hash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $hash;
    }

    public function getId(): string
    {
        return $this->id;
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