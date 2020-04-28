<?php

declare(strict_types=1);

namespace App\Project\Domain\Query;

interface ProjectReadModelInterface
{
    public function getId(): string;

    public function getName(): string;
}