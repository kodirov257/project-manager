<?php

declare(strict_types=1);

namespace App\ReadModel\User;

class DetailView
{
    public string $id;
    public \DateTimeImmutable $date;
    public ?string $first_name;
    public ?string $last_name;
    public ?string $email;
    public string $role;
    public string $status;
    /** @var NetworkView[] */
    public ?array $networks;

    public function __construct(string $id, \DateTimeImmutable $date, ?string $firstName, ?string $lastName, ?string $email, string $role, string $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
        $this->networks = null;
    }
}