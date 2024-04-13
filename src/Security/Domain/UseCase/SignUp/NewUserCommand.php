<?php

declare(strict_types=1);

namespace App\Security\Domain\UseCase\SignUp;

use App\Core\Domain\CQRS\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class NewUserCommand implements Command
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_WEAK)]
    #[Assert\NotCompromisedPassword]
    public string $password;
}
