<?php

declare(strict_types=1);

namespace App\Organization\Domain\Query;

interface OrganizationReadModelInterface
{
    public function getId(): string;

    public function getName(): string;
}