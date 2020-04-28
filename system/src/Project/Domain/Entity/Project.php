<?php

declare(strict_types=1);

namespace App\Project\Domain\Entity;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;
use App\Task\Domain\Entity\Task;
use Ramsey\Uuid\UuidInterface;

class Project
{
    protected UuidInterface $id;
    protected UuidInterface $organizationId;
    protected string $name;
    private array $tasks;

    public function __construct(ProjectId $id, OrganizationId $organizationId, string $name)
    {
        $this->id = $id->get();
        $this->organizationId = $organizationId->get();

        $this->name = $name;
    }

    public function getId(): ProjectId
    {
        return ProjectId::createFromUuid($this->id);
    }

    public function getName(): ProjectName
    {
        return ProjectName::create($this->name);
    }

    public function addTask(Task $task): void
    {
        $this->tasks[] = $task;
    }
}