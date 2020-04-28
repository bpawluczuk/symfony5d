<?php

declare(strict_types=1);

namespace App\User\Application;

use App\Shared\Application\HandlerInterface;
use App\User\Domain\Exception\PasswordMismatchException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;

class UpdateUserPasswordHandler implements HandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateUserPasswordCommand $command
     * @throws PasswordMismatchException
     * @throws UserNotFoundException
     */
    public function __invoke(UpdateUserPasswordCommand $command)
    {
        $user = $this->userRepository->getById($command->getUserId());

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->updatePassword(
            $command->getOldPassword(),
            $command->getNewPassword()
        );

        $this->userRepository->save($user);
    }
}