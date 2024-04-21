<?php

declare(strict_types=1);

namespace Tests\Component\Hasher;

use App\Core\Domain\Model\ValueObject\Email;
use App\Core\Domain\Model\ValueObject\Identifier;
use App\Security\Domain\Model\Entity\User;
use App\Security\Domain\Model\ValueObject\PlainPassword;
use App\Security\Infrastructure\Hasher\PasswordHasher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PasswordHasherTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testShouldHashAndVerifyPasswordSuccessfully(): void
    {
        $container = static::getContainer();

        /** @var UserPasswordHasherInterface $userPasswordHasher */
        $userPasswordHasher = $container->get(UserPasswordHasherInterface::class);

        $passwordHasher = new PasswordHasher($userPasswordHasher);

        $plainPassword = PlainPassword::create('Password123!');

        self::assertTrue(
            $passwordHasher->verify(
                $plainPassword,
                new User(
                    Identifier::generate(),
                    Email::create('user@email.com'),
                    $passwordHasher->hash($plainPassword)
                )
            )
        );
    }
}
