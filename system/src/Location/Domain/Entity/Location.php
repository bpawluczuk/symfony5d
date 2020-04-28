<?php

declare(strict_types=1);

namespace App\Location\Domain\Entity;

use App\Organization\Domain\Value\OrganizationId;
use App\Location\Domain\Value\LocationId;
use Ramsey\Uuid\UuidInterface;

class Location
{
    private UuidInterface $id;
    private UuidInterface $organizationId;

    public function __construct(LocationId $id, OrganizationId $organizationId)
    {
        $this->id = $id->get();
        $this->organizationId = $organizationId->get();
    }

    public function getId(): LocationId
    {
        return LocationId::createFromUuid($this->id);
    }

}