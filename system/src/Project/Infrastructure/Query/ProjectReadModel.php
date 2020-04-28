<?php

declare(strict_types=1);

namespace App\Project\Infrastructure\Query;

use App\Project\Domain\Query\ProjectReadModelInterface;
use Swagger\Annotations as SWG;

class ProjectReadModel implements ProjectReadModelInterface
{
    /**
     * @SWG\Property(type="string", description="Project Id")
     */
    protected string $id;

    /**
     * @SWG\Property(type="string", description="Project name")
     */
    protected string $name;

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