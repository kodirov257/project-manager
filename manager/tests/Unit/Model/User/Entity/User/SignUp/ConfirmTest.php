<?php

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignupUser();

        $user->confirmSignup();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = $this->buildSignupUser();

        $user->confirmSignup();
        $this->expectExceptionMessage('User is already confirmed.');
        $user->confirmSignup();
    }

    private function buildSignupUser(): User
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
        );

        $user->signUpByEmail(
            new Email(
                'test@app.local',
            ),
            'hash',
            'token',
        );

        return $user;
    }
}