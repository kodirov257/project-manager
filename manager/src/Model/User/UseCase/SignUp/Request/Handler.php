<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;
    private ConfirmTokenizer $tokenizer;
    private ConfirmTokenSender $sender;
    private Flusher $flusher;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        ConfirmTokenizer $tokenizer,
        ConfirmTokenSender $sender,
        Flusher $flusher,
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate(),
        );

        $this->users->add($user);

        $this->sender->send($email, $token);

        $this->flusher->flush();
    }
}