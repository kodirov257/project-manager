<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Activate;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;

class Handler
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Flusher $flusher,
    )
    {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->activate();

        $this->flusher->flush();
    }
}