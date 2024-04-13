<?php

declare(strict_types=1);

namespace Tests\Fixtures\Core\Doctrine\Repository;

use App\Core\Domain\Model\ValueObject\Email;
use App\Security\Domain\Model\Entity\User;
use App\Security\Domain\Port\Repository\UserRepository;

final class FakeUserRepository implements UserRepository
{
    /**
     * @var array<string, User>
     */
    public array $users = [];

    public function register(User $user): void
    {
        $this->users[$user->email()->value()] = $user;
    }

    public function isAlreadyUsed(Email|string $email): bool
    {
        return isset($this->users[(string)$email]);
    }

    public function findByEmail(Email $email): ?User
    {
        return $this->users[$email->value()] ?? null;
    }
}
