<?php

declare(strict_types=1);

namespace App\ReadModel\User;

class DetailView
{
    public string $id;
    public \DateTimeImmutable $date;
    public ?string $email;
    public string $role;
    public string $status;
    /** @var NetworkView[] */
    public ?array $networks;

    public function __construct(string $id, \DateTimeImmutable $date, ?string $email, string $role, string $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
        $this->networks = null;
    }
}