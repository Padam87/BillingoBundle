<?php

namespace Padam87\BillingoBundle\Service;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

    public function request(string $method, string $uri, array $data = [], bool $raw = false)
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

        $response = $this->client->request($method, $uri, $options);

        if ($raw) {
            return $response->getContent();
        }

        return $response->toArray();
    }
}
