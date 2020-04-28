<?php

declare(strict_types=1);

namespace App\Task\Domain\Factory;

use App\Project\Domain\Value\ProjectId;
use App\Task\Domain\Entity\Task;
use App\Task\Domain\Value\TaskId;

class TaskFactory
{
    public function createTask(ProjectId $projectId, string $name) :Task
    {
        return new Task(
            TaskId::create(),
            $projectId,
            $name
        );
    }
}