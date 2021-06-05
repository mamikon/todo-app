<?php


namespace App\Auth;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Ramsey\Uuid\Uuid;


class ApiAuthTest extends \App\AbstractFunctionalTest
{

    public function test_it_should_successfully_authenticate()
    {
        $client = $this->createClientWithCredentials();
        $client->request('GET', 'api');
        $this->assertResponseIsSuccessful();
    }
}