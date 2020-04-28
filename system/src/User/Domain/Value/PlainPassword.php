<?php

declare(strict_types=1);

namespace App\User\Domain\Value;

class PlainPassword
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public static function create(string $password): self
    {
        return new self($password);
    }

    public function getPlain(): string
    {
        return $this->password;
    }

    public function getHashed(): string
    {
        return password_hash(
            $this->password,
            PASSWORD_BCRYPT,
            ['cost' => 12]
        );
    }

}