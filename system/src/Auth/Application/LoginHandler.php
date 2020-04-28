<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\Auth\Domain\Exception\InvalidCredentialsException;
use App\Shared\Application\HandlerInterface;
use App\User\Domain\Repository\UserRepositoryInterface;

class LoginHandler implements HandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(LoginCommand $command)
    {
        $user = $this->userRepository->getByUsername($command->getUsername());

        if (!$user || !$user->checkPassword($command->getPlainPassword())) {
            throw new InvalidCredentialsException('Invalid credentials');
        }
    }
}
