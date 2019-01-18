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

    /**
     * @param Authenticator $authenticator
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @return Client|null
     */
    public function getClient()
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

    /**
     * @param string $method
     * @param string $uri
     * @param array  $data
     *
     * @return array
     */
    public function request($method, $uri, array $data = [])
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

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}
