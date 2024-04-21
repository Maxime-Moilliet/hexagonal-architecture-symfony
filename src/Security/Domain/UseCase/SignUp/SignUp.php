<?php

declare(strict_types=1);

namespace App\Security\Domain\UseCase\SignUp;

use App\Core\Domain\CQRS\Handler;
use App\Core\Domain\Model\ValueObject\Email;
use App\Core\Domain\Model\ValueObject\Identifier;
use App\Security\Domain\Model\ValueObject\PlainPassword;
use App\Security\Domain\Port\Hasher\PasswordHasherInterface;
use App\Security\Domain\Port\Repository\UserRepository;
use App\Security\Domain\Model\Entity\User;

final readonly class SignUp implements Handler
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(NewUserCommand $newUserCommand): void
    {
        $user = new User(
            Identifier::generate(),
            Email::create($newUserCommand->email),
            $this->passwordHasher->hash(PlainPassword::create($newUserCommand->password)),
        );

        $this->userRepository->register($user);
    }
}
