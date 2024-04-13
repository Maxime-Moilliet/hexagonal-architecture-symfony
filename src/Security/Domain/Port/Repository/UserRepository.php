<?php

declare(strict_types=1);

namespace App\Security\Domain\Port\Repository;

use App\Core\Domain\Model\ValueObject\Email;
use App\Security\Domain\Model\Entity\User;

interface UserRepository
{
    public function register(User $user): void;

    public function isAlreadyUsed(Email|string $email): bool;

    public function findByEmail(Email $email): ?User;
}
