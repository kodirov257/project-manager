<?php

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

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
        return new User(
            Id::next(),
            new \DateTimeImmutable(),
            new Email('test@app.test'),
            'hash',
            'token',
        );
    }
}