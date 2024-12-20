<?php

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function __construct(private readonly PasswordHasher $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('123456');

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            new Name('Jack', 'Sparrow'),
            new Email('admin@app.test'),
            $hash,
            'token',
        );

        $user->confirmSignup();

        $user->changeRole(Role::admin());

        $manager->persist($user);

        $manager->flush();

        $manager->flush();
    }
}
