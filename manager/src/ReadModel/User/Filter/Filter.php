<?php

declare(strict_types=1);

namespace App\ReadModel\User\Filter;

class Filter
{
    public ?string $name;
    public ?string $email;
    public ?string $role;
    public ?string $status;

    public function __construct()
    {
        $this->name = null;
        $this->email = null;
        $this->role = null;
        $this->status = null;
    }
}