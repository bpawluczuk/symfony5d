<?php

declare(strict_types=1);

namespace App\Auth\Domain\Query;

interface SessionReadModelInterface
{
    public function getId(): string;
}