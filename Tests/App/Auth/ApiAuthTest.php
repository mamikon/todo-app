<?php


namespace App\Auth;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Ramsey\Uuid\Uuid;


class ApiAuthTest extends ApiTestCase
{

    public function test_it_should_successfully_authenticate()
    {
        $client = self::createClient();

        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUuid(Uuid::uuid4()->toString());
        $user->setPassword(
            self::getContainer()->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
        );

        $manager = self::getContainer()->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/api/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json'    => [
                'email'    => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);
        $json     = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);
        $this->assertResponseIsSuccessful();
    }
}