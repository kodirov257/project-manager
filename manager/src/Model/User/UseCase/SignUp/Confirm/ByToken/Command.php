<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm\ByToken;

class Command
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}