<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure;

interface UserIdAwareInterface
{
    public function getId(): string;
}