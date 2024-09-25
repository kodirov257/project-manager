<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;

class UserBuilder
{
    private Id $id;
    private \DateTimeImmutable $date;

    private ?Email $email;
    private ?string $hash;
    private ?string $token;

    private ?string $network;
    private ?string $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;

        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';

        return $clone;
    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;

        $clone->network = $network ?? 'facebook';
        $clone->identity = $identity ?? '00001';

        return $clone;
    }

    public function build(): User
    {
        $user = new User($this->id, $this->date);

        if ($this->email) {
            $user->signUpByEmail($this->email, $this->hash, $this->token);
        }

        if ($this->network) {
            $user->signUpByNetwork($this->network, $this->identity);
        }

        return $user;
    }
}