<?php

namespace Padam87\BillingoBundle\Tests\Service;

use Firebase\JWT\JWT;
use Padam87\BillingoBundle\Service\Authenticator;
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase
{
    protected function getConfig(): array
    {
        return [
            'authentication' => [
                'public_key' => 'public_key',
                'private_key' => 'private_key',
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
    public function getAuthKey()
    {
        $authenticator = new Authenticator($this->getConfig());

        $this->assertNotNull($authenticator->getAuthKey());
    }

    /**
     * @test
     */
    public function getAuthKeyTwice()
    {
        $authenticator = new Authenticator($this->getConfig());

        $this->assertSame($authenticator->getAuthKey(), $authenticator->getAuthKey());
    }

    /**
     * @test
     */
    public function keyLifetime()
    {
        $config = $this->getConfig();
        $authenticator = new Authenticator($config);

        $key = $authenticator->getAuthKey();

        $details = JWT::decode($key, $config['authentication']['private_key'], ['HS256']);

        $this->assertEquals($config['authentication']['lifetime'], $details->exp - $details->iat);
    }
}
