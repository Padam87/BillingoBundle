<?php

namespace Padam87\BillingoBundle\Service;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Api
{
    protected HttpClientInterface $client;

    public function __construct(?HttpClientInterface $client, protected array $config)
    {
        $this->client = $client ?? new CurlHttpClient();
    }

    public function request(string $method, string $uri, array $data = []): ResponseInterface
    {
        $options = [
            'base_uri' => $this->config['api']['base_url'],
            'headers' => [
                'X-API-KEY' => $this->config['authentication']['token'],
            ]
        ];

        if ($data !== []) {
            $options['json'] = $data;
        }

        return $this->client->request($method, $uri, $options);
    }
}
