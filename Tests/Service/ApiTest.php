<?php

namespace Padam87\BillingoBundle\Tests\Service;

use Padam87\BillingoBundle\Service\Api;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiTest extends TestCase
{
    protected function getConfig(): array
    {
        return [
            'authentication' => [
                'token' => 'token',
                'lifetime' => 900,
                'time_offset' => -180,
            ],
            'api' => [
                'version' => 2,
                'base_url' => 'https://www.billingo.hu/api/',
            ],
        ];
    }

    /**
     * @test
     */
    public function request()
    {
        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();

        $api = new Api($client, $this->getConfig());
        $response = $api->request('GET', '/time');

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
