<?php

declare(strict_types=1);

namespace Tests\Integration;

use Symfony\Component\HttpFoundation\Response;

trait ApiAssertionsTrait
{
    public static function assertJsonResponse(mixed $data): void
    {
        if (($client = self::getClient()) === null) {
            throw new \RuntimeException('The client is not set.');
        }

        self::assertResponseHeaderSame('Content-Type', 'application/json');

        /** @var Response $response */
        $response = $client->getResponse();

        self::assertJsonStringEqualsJsonString(json_encode($data), (string)$response->getContent());
    }
}