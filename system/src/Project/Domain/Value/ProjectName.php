<?php

declare(strict_types=1);

namespace App\Project\Domain\Value;

final class ProjectName
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
}