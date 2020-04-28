<?php

declare(strict_types=1);

namespace App\Organization\Domain\Entity;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Entity\Project;
use App\User\Domain\Entity\User;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;
use App\Location\Domain\Entity\Location;
use Ramsey\Uuid\UuidInterface;

class Organization
{
    private UuidInterface $id;
    private string $name;
    private string $logoUrl;
    private array $users;
    private array $locations;
    private array $projects;

    public function __construct(OrganizationId $id, string $name, string $logoUrl)
    {
        $this->id = $id->get();
        $this->name = $name;
        $this->logoUrl = $logoUrl;
    }

    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }

    public function addLocation(Location $location): void
    {
        $this->locations[] = $location;
    }

    public function hasUser(Username $username): bool
    {
        return array_reduce($this->users, function (bool $carry, User $user) use ($username) {
            return $username->equalsTo($user->getUsername());
        }, false);
    }

    public function removeUser(UserId $userId): void
    {

    }

    public function addProject(Project $project)
    {
        $this->projects[] = $project;
    }

    public function getId(): OrganizationId
    {
        return OrganizationId::createFromUuid($this->id);
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