<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\DataFixtures;

use App\Core\Infrastructure\Doctrine\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = self::createNewUser();
        $manager->persist($user);
        $manager->flush();
    }

    private static function createNewUser(
        string $email = 'admin+1@email.com',
        string $password = 'Password123!'
    ): User
    {
        $user = new User();
        $user->id = new Ulid();
        $user->email = $email;
        $user->password = $password;

        return $user;
    }
}
