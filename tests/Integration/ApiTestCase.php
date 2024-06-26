<?php

declare(strict_types=1);

namespace Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    use ApiAssertionsTrait;

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $query
     */
    protected function post(string $url, array $body = [], ?array $query = null): Response
    {
        /** @var AbstractBrowser $client */
        $client = self::getClient();

        if (null !== $query) {
            $url = sprintf('%s?%s', $url, http_build_query($query));
        }

        $client->request(
            'post',
            $url,
            [],
            [],
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            (string) json_encode($body)
        );

        /** @var Response $response */
        $response = $client->getResponse();

        return $response;
    }
}
