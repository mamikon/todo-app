<?php

namespace App\Auth;

class ApiAuthTest extends \App\AbstractFunctionalTest
{
    public function testItShouldSuccessfullyAuthenticate()
    {
        $client = $this->createClientWithCredentials();
        $client->request('GET', 'api');
        $this->assertResponseIsSuccessful();
    }
}
