<?php

namespace App;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;

abstract class AbstractFunctionalTest extends ApiTestCase
{

    private ?string $token = null;

    public function setUp(): void
    {
        self::bootKernel();

    }

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => ['Authorization' => 'Bearer ' . $token]]);
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($body = [], $force = false): string
    {
        if ($this->token && !$force) {
            return $this->token;
        }
        $mail = rand() . 'test@example.com';
        if (empty($body)) {
            static::createClient()->request('POST', '/api/users', [
                'headers' => [
                    'content-type' => 'application/json',
                ],

                'json' => [
                    'email'    => $mail,
                    'password' => '$3cr3tP4$$',
                ]
            ]);
        }
        $response = static::createClient()->request('POST', '/api/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json'    => [
                'email'    => $mail,
                'password' => '$3cr3tP4$$',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $data        = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }

}