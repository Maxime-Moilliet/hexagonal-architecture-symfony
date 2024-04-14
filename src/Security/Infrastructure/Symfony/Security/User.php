<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Symfony\Security;

use App\Security\Domain\Model\Entity\User as DomainUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class User implements PasswordAuthenticatedUserInterface
{
    private function __construct(private DomainUser $user)
    {
    }

    public static function create(DomainUser $user): self
    {
        return new self($user);
    }


    public function getPassword(): ?string
    {
        return $this->user->password()->value();
    }
}
