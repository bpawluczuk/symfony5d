<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Value;

use App\Auth\Infrastructure\UserIdAwareInterface;
use App\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Auth implements UserInterface, EncoderAwareInterface, UserIdAwareInterface
{
    private User $user;

    public function __construct(User $userReadModel)
    {
        $this->user = $userReadModel;
    }

    public static function createFromEntity(User $user): self
    {
        return new static($user);
    }

    public function getId(): string
    {
        return $this->user->getId()->toString();
    }

    public function getEncoderName()
    {
        return 'bcrypt';
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->user->getHashedPassword();
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function eraseCredentials()
    {
    }
}