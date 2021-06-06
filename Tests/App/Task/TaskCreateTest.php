<?php

namespace App\Task;

use App\AbstractFunctionalTest;
use Ramsey\Uuid\Uuid;
use TaskManagement\Domain\Task\Status;

class TaskCreateTest extends AbstractFunctionalTest
{
    public function testItShouldCreateTask()
    {
        $client   = $this->createClientWithCredentials();
        $response = $client->request(
            'POST',
            'api/tasks',
            [
                'json' => [
                    'title'       => 'test',
                    'description' => 'test',
                    'status'      => current(Status::getStatusLabels()),
                    'date'        => date('Y-m-d'),
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
        $data = \json_decode($response->getContent());
        $this->assertTrue(Uuid::isValid($data->uuid));
        $connection = self::getContainer()->get('doctrine')->getConnection();
        $result     = $connection->fetchAllAssociative('select * from tasks where uuid = ? limit 1', [$data->uuid]);
        $this->assertCount(1, $result);
    }

    public function testTaskNotCreatedWithInvalidStatus()
    {
        $client = $this->createClientWithCredentials();
        $client->request(
            'POST',
            'api/tasks',
            [
                'json' => [
                    'title'       => 'test',
                    'description' => 'test',
                    'status'      => 'inv4lid_status_',
                    'date'        => date('Y-m-d'),
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(422);
    }
}
