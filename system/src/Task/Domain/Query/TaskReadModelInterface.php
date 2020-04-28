<?php

declare(strict_types=1);

namespace App\Task\Domain\Query;

interface TaskReadModelInterface
{
    public function getId(): string;

    public function getName(): string;
}