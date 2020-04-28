<?php

declare(strict_types=1);

namespace App\Location\Infrastructure\Query;

use App\Location\Domain\Query\LocationReadModelInterface;
use Swagger\Annotations as SWG;

class LocationReadModel implements LocationReadModelInterface
{
    /**
     * @SWG\Property(type="string", description="Location Id")
     */
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}