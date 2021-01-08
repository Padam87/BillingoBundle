<?php

namespace Padam87\BillingoBundle\Service;

use Firebase\JWT\JWT;

class Authenticator
{
    protected array $config;
    protected ?string $authKey = null;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get or generate JWT authorization key
     */
    public function getAuthKey(): string
    {
        if ($this->authKey) {
            try {
                JWT::decode($this->authKey, $this->config['authentication']['private_key'], ['HS256']);
            } catch (\Exception $e) {
                $this->authKey = null;
            }
        }

        if ($this->authKey === null) {
            $time = time() + $this->config['authentication']['time_offset'];

            $this->authKey = JWT::encode(
                [
                    'sub' => $this->config['authentication']['public_key'],
                    'iat' => $time,
                    'exp' => $time + $this->config['authentication']['lifetime'],
                    'iss' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'cli',
                    'nbf' => $time,
                    'jti' => md5($this->config['authentication']['public_key'] . $time)
                ],
                $this->config['authentication']['private_key']
            );
        }

        return $this->authKey;
    }
}
