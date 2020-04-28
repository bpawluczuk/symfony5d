<?php

declare(strict_types=1);

namespace App\Tests\Project\Ui\Http;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Value\OrganizationId;
use App\Organization\Infrastructure\Repository\OrganizationRepository;
use App\Project\Domain\Factory\ProjectReadModelFactory;
use App\Project\Domain\Query\ProjectReadModelInterface;
use App\Project\Domain\Value\ProjectId;
use App\Project\Infrastructure\Query\ProjectReadModel;
use App\Project\Infrastructure\Query\ProjectReadModelRepository;
use App\Tests\ItseApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends ItseApiTestCase
{
    public function testProjectWillNotBeFetchedIfOrganizationFilterIsNotProvided()
    {
        $this->assertResponse(
            $this->get(
                sprintf('/api/projects/%s', ProjectId::create()->toString())
            ),
            'error_message',
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testProjectWillNotBeFetchedIfOrganizationFilterIsInvalid()
    {
        $this->assertResponse(
            $this->get(
                sprintf('/api/projects/%s', ProjectId::create()->toString()),
                [
                    'organizationId' => 'id'
                ]
            ),
            'error_message',
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testProjectWillNotBeFetchedIfProjectIdIsInvalid()
    {
        $this->assertResponseCode(
            $this->get(
                sprintf('/api/projects/%s', 'id')
            ),
            Response::HTTP_NOT_FOUND
        );
    }

    public function testProjectWillNotBeFetchedIfDoesNotExist()
    {
        $projectReadModelRepository = $this->createMock(ProjectReadModelRepository::class);
        $projectReadModelRepository
            ->expects($this->once())
            ->method('getOneByIdInOrganization')
            ->willReturn(null);

        self::$container->set(ProjectReadModelRepository::class, $projectReadModelRepository);

        $this->assertResponseCode(
            $this->get(
                sprintf('/api/projects/%s', ProjectId::create()->toString()),
                [
                    'organizationId' => OrganizationId::create()->toString()
                ]
            ),
            Response::HTTP_NOT_FOUND
        );
    }

    public function testProjectWillBeFetchedByIdAndOrganizationIfExists()
    {
        $projectReadModelRepository = $this->createMock(ProjectReadModelRepository::class);
        $projectReadModelRepository
            ->expects($this->once())
            ->method('getOneByIdInOrganization')
            ->willReturn($this->getProjectReadModel());

        self::$container->set(ProjectReadModelRepository::class, $projectReadModelRepository);

        $this->assertResponse(
            $this->get(
                sprintf('/api/projects/%s', ProjectId::create()->toString()),
                [
                    'organizationId' => OrganizationId::create()->toString()
                ]
            ),
            'project_by_id_and_organization',
            Response::HTTP_OK
        );
    }

    public function testAllProjectsWillNotBeFetchedIfOrganizationFilterIsNotProvided()
    {
        $this->assertResponse(
            $this->get('/api/projects'),
            'error_message',
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testAllProjectsWillNotBeFetchedIfOrganizationFilterIsInvalid()
    {
        $this->assertResponse(
            $this->get('/api/projects', [
                    'organizationId' => 'id'
                ]
            ),
            'error_message',
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testProjectWillNotBeAddedIfOrganizationDoesNotExist()
    {
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(null);

        self::$container->set(OrganizationRepository::class, $organizationRepository);

        $this->assertResponseCode(
            $this->post(
                sprintf(
                    '/api/projects?%s',
                    http_build_query(['organizationId' => OrganizationId::create()->toString()])
                ),
                [
                    'name' => 'Project name'
                ]
            ),
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testProjectWillNotBeAddedIfAlreadyExists()
    {
        $organization = $this->createMock(Organization::class);
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($organization);

        self::$container->set(OrganizationRepository::class, $organizationRepository);

        $projectReadModel = $this->createMock(ProjectReadModel::class);
        $projectReadModelRepository = $this->createMock(ProjectReadModelRepository::class);
        $projectReadModelRepository
            ->expects($this->once())
            ->method('getOneByNameInOrganization')
            ->willReturn($projectReadModel);

        self::$container->set(ProjectReadModelRepository::class, $projectReadModelRepository);

        $this->assertResponseCode(
            $this->post(
                sprintf(
                    '/api/projects?%s',
                    http_build_query(['organizationId' => OrganizationId::create()->toString()])
                ),
                [
                    'name' => 'Project name'
                ]
            ),
            Response::HTTP_CONFLICT
        );
    }

    public function testProjectWillNotBeAddedIfDataIsInvalid()
    {
        $this->assertResponseCode(
            $this->post(
                sprintf(
                    '/api/projects?%s',
                    http_build_query(['organizationId' => OrganizationId::create()->toString()])
                ),
                [
                    'name' => ''
                ]
            ),
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testFormErrorsWillBeDisplayedIfProjectDataIsInvalid()
    {
        $this->assertResponse(
            $this->post(
                sprintf(
                    '/api/projects?%s',
                    http_build_query(['organizationId' => OrganizationId::create()->toString()])
                ),
                [
                    'name' => ''
                ]
            ),
            'project_form_errors',
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testProjectWillBeCreatedIfDataIsValid()
    {
        $organization = $this->createMock(Organization::class);
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($organization);

        self::$container->set(OrganizationRepository::class, $organizationRepository);

        $projectReadModelRepository = $this->createMock(ProjectReadModelRepository::class);
        $projectReadModelRepository
            ->expects($this->once())
            ->method('getOneByNameInOrganization')
            ->willReturn(null);

        self::$container->set(ProjectReadModelRepository::class, $projectReadModelRepository);

        $this->assertResponseCode(
            $this->post(
                sprintf(
                    '/api/projects?%s',
                    http_build_query(['organizationId' => OrganizationId::create()->toString()])
                ),
                [
                    'name' => 'project name'
                ]
            ),
            Response::HTTP_CREATED
        );
    }

    protected function getProjectReadModel()
    {
        $projectReadModelFactory = new ProjectReadModelFactory();

        return $projectReadModelFactory->create(ProjectId::create()->toString(), 'project name');
    }
}