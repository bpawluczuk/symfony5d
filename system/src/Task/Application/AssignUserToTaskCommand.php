<?php

declare(strict_types=1);

namespace App\Task\Application;

use App\Task\Domain\Value\TaskId;
use App\User\Domain\Value\UserId;

class AssignUserToTaskCommand
{
    private TaskId $taskId;
    private UserId $userId;

    public function __construct(TaskId $taskId, UserId $userId)
    {
        $this->taskId = $taskId;
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}