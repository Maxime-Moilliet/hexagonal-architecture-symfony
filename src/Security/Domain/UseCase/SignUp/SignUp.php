<?php

declare(strict_types=1);

namespace App\Security\Domain\UseCase\SignUp;

use App\Core\Domain\CQRS\Handler;
use App\Core\Domain\Model\ValueObject\Email;
use App\Security\Domain\Model\Factory\RegisterUserFactory;
use App\Security\Domain\Model\ValueObject\Password;
use App\Security\Domain\Port\Repository\UserRepository;

final readonly class SignUp implements Handler
{
    public function __construct(
        private UserRepository      $userRepository,
        private RegisterUserFactory $registerUserFactory
    )
    {
    }

    public function __invoke(NewUserCommand $newUserCommand): void
    {
        $user = $this->registerUserFactory
            ->withEmail(Email::create($newUserCommand->email))
            ->withPassword(Password::create($newUserCommand->password))
            ->build();

        $this->userRepository->register($user);
    }
}
