<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\User\Domain\Exception\PasswordMismatchException;
use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;
use Ramsey\Uuid\UuidInterface;

class User
{
    private UuidInterface $id;
    private string $username;
    private string $hashedPassword;

    public function __construct(UserId $id, string $username, string $hashedPassword)
    {
        $this->id = $id->get();
        $this->username = $username;
        $this->hashedPassword = $hashedPassword;
    }

    public function getId(): UserId
    {
        return UserId::createFromUuid($this->id);
    }

    public function getUsername(): Username
    {
        return Username::create($this->username);
    }

    public function checkPassword(PlainPassword $plainPassword): bool
    {
        return password_verify(
            $plainPassword->getPlain(),
            $this->hashedPassword
        );
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function updatePassword(PlainPassword $oldPassword, PlainPassword $newPassword): void
    {
        if (!$this->checkPassword($oldPassword)) {
            throw new PasswordMismatchException();
        }

        $this->hashedPassword = $newPassword->getHashed();
    }
}