<?php

declare(strict_types=1);

namespace App\Organization\Infrastructure\Query;

use App\Organization\Domain\Query\OrganizationReadModelInterface;
use Swagger\Annotations as SWG;

class OrganizationReadModel implements OrganizationReadModelInterface
{
    /**
     * @SWG\Property(type="string", description="Unique Id for the Organization")
     */
    private string $id;

    /**
     * @SWG\Property(type="string", description="Organization name")
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