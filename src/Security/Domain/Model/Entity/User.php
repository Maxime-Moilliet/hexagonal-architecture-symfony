<?php

declare(strict_types=1);

namespace App\Security\Domain\Model\Entity;

use App\Core\Domain\Model\ValueObject\Email;
use App\Core\Domain\Model\ValueObject\Identifier;
use App\Security\Domain\Model\ValueObject\Password;

final readonly class User
{
    private function __construct(
        private Identifier $id,
        private Email $email,
        private Password $password,
    ) {
    }

    public static function register(Email $email, Password $password): self
    {
        return new self(Identifier::generate(), $email, $password);
    }

    public static function create(Identifier $id, Email $email, Password $password): self
    {
        return new self($id, $email, $password);
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
