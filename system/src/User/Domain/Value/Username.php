<?php

declare(strict_types=1);

namespace App\User\Domain\Value;

class Username
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function create(string $username): self
    {
        return new self($username);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function equalsTo(self $username)
    {
        return $this->username === $username->getUsername();
    }
}