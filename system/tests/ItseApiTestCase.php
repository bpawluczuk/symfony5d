<?php

declare(strict_types=1);

namespace App\Tests;

use ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ItseApiTestCase extends JsonApiTestCase
{
    protected function get(string $url, array $params = []): Response
    {
        $this->client->request(
            'GET',
            $url,
            $params,
        );

        return $this->client->getResponse();
    }

    protected function post(string $url, array $payload): Response
    {
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload),
        );

        return $this->client->getResponse();
    }

    protected function put(string $url, array $payload = []): Response
    {
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload),
        );

        return $this->client->getResponse();
    }

    protected function patch(string $url, array $payload = []): Response
    {
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload),
        );

        return $this->client->getResponse();
    }

    protected function delete(string $url, array $params = []): Response
    {
        $this->client->request(
            'DELETE',
            $url,
        );

        return $this->client->getResponse();
    }
}