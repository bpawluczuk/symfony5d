<?php

declare(strict_types=1);

namespace App\Task\Application;

use App\Shared\Application\HandlerInterface;
use App\Task\Domain\Exception\AssignmentUserWithTaskAlreadyExistsException;
use App\Task\Domain\Exception\TaskNotFoundException;
use App\Task\Domain\Repository\TaskRepositoryInterface;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;

class AssignUserToTaskHandler implements HandlerInterface
{
    private TaskRepositoryInterface $taskRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(AssignUserToTaskCommand $command)
    {
        $task = $this->taskRepository->getById($command->getTaskId());

        if (!$task) {
            throw new TaskNotFoundException();
        }

        $user = $this->userRepository->getById($command->getUserId());

        if (!$user) {
            throw new UserNotFoundException();
        }

        $assignmentUserToTaskExists = $task->hasUser($user->getId());

        if ($assignmentUserToTaskExists) {
            throw new AssignmentUserWithTaskAlreadyExistsException();
        }

        $task->addUser($user);
        $this->taskRepository->save($task);
    }

}