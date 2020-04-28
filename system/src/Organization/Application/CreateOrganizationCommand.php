<?php

declare(strict_types=1);

namespace App\Organization\Application;

class CreateOrganizationCommand
{
    private string $name;
    private string $logoUrl;

    public function __construct(string $name, string $logoUrl)
    {
        $this->name = $name;
        $this->logoUrl = $logoUrl;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogoUrl(): string
    {
        return $this->logoUrl;
    }
}