<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm\Manual;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;

class Handler
{
    private UserRepository $users;
    private Flusher $flusher;

    public function __construct(
        UserRepository         $users,
        Flusher                $flusher,
    )
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}