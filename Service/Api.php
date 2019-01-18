<?php

namespace Padam87\BillingoBundle\Service;

use GuzzleHttp\Client;

class Api
{
    /**
     * @var Authenticator
     */
    protected $authenticator;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Client|null
     */
    protected $client = null;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function getClient(): ?Client
    {
        if ($this->client === null) {
            $this->client = new Client(
                [
                    'verify' => false,
                    'debug' => false,
                    'base_uri' => $this->config['base_url']
                ]
            );
        }

        return $this->client;
    }

    public function request(string $method, string $uri, array $data = [], bool $raw = false)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authenticator->getAuthKey(),
            ]
        ];

        if (!empty($data)) {
            $options['json'] = $data;
        }

        $response = $this->getClient()->request($method, $uri, $options);

        if ($raw) {
            return $response->getBody();
        }

        if (null === $responseData = @json_decode($response->getBody(), true)) {
            throw new \UnexpectedValueException(
                sprintf('There was an error decoding the response. %s', json_last_error_msg())
            );
        }

        return $responseData;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }
}
