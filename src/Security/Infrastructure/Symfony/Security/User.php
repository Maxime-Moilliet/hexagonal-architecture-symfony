<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Symfony\Security;

use App\Security\Domain\Model\Entity\User as DomainUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class User implements PasswordAuthenticatedUserInterface
{
    public function __construct(private DomainUser $user)
    {
    }

    public function getPassword(): string
    {
        return $this->user->password()->value();
    }
}
