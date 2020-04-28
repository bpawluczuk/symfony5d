<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Query;

use App\Task\Domain\Query\TaskReadModelInterface;
use Swagger\Annotations as SWG;

class TaskReadModel implements TaskReadModelInterface
{
    /**
     * @SWG\Property(type="string", description="Unique Id for the Task")
     */
    private string $id;

    /**
     * @SWG\Property(type="string", description="Task name")
     */
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}