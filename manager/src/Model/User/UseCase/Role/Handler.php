<?php

namespace App\Model\User\UseCase\Role;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\UserRepository;

class Handler
{

    public function __construct(private readonly UserRepository $users, private readonly Flusher $flusher)
    {}

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->changeRole(new Role($command->role));

        $this->flusher->flush();
    }
}