<?php

declare(strict_types=1);

namespace App\User\Domain\Query;

interface UserReadModelInterface
{
    public function getId(): string;

    public function getUsername(): string;
}