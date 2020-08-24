<?php

namespace Padam87\BillingoBundle\Service;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Api
{
    protected $authenticator;
    protected $client;
    protected $config;

    public function __construct(Authenticator $authenticator, ?HttpClientInterface $client, array $config)
    {
        $this->authenticator = $authenticator;
        $this->client = $client ?? new CurlHttpClient();
        $this->config = $config;
    }

    public function request(string $method, string $uri, array $data = []): ResponseInterface
    {
        $options = [
            'base_uri' => $this->config['api']['base_url'],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authenticator->getAuthKey(),
            ]
        ];

        if (!empty($data)) {
            $options['json'] = $data;
        }

        return $this->client->request($method, $uri, $options);
    }
}
