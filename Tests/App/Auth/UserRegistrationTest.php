<?php

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

class UserRegistrationTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testItShouldRegisterUser()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/users',
            [
                'headers' => [
                    'content-type' => 'application/json',
                ],

                'body' => json_encode(
                    [
                        'email'    => 'admin@example.com',
                        'password' => '$3cr3t',
                    ]
                ),
            ]);
        $this->assertResponseIsSuccessful();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser       = $userRepository->findOneByEmail('admin@example.com');
        $this->assertTrue(Ramsey\Uuid\Uuid::isValid($testUser->getUuid()));
    }
}
