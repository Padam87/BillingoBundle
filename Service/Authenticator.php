<?php

namespace Padam87\BillingoBundle\Service;

use Firebase\JWT\JWT;

class Authenticator
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $authKey = null;

    /**
     * Get or generate JWT authorization key
     */
    public function getAuthKey(): string
    {
        if ($this->authKey === null) {
            $time = time() + $this->config['time_offset'];

            $this->authKey = JWT::encode(
                [
                    'sub' => $this->config['public_key'],
                    'iat' => $time,
                    'exp' => $time + $this->config['lifetime'],
                    'iss' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'cli',
                    'nbf' => $time,
                    'jti' => md5($this->config['public_key'] . $time)
                ],
                $this->config['private_key']
            );
        }

        return $this->authKey;
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
