<?php

declare(strict_types=1);

namespace App\Task\Application;

use App\Project\Domain\Value\ProjectId;

class AddTaskToProjectCommand
{
    private ProjectId $projectId;
    private string $name;

    public function __construct(ProjectId $projectId, string $name)
    {
        $this->projectId = $projectId;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }
}