<?php

declare(strict_types=1);

namespace App\Security\Domain\Model\Entity;

use App\Core\Domain\Model\ValueObject\Email;
use App\Core\Domain\Model\ValueObject\Identifier;
use App\Security\Domain\Model\ValueObject\Password;

final readonly class User
{
    public function __construct(
        private Identifier $id,
        private Email $email,
        private Password $password,
    ) {
    }

    public function id(): Identifier
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
