<?php

namespace App\Task;

use App\AbstractFunctionalTest;
use TaskManagement\Domain\Task\Status;

class TaskUpdateTest extends AbstractFunctionalTest
{
    private function createTask($client)
    {
        $response = $client->request('POST', 'api/tasks', ['json' => [
            'title'       => 'test',
            'description' => 'test',
            'status'      => current(Status::getStatusLabels()),
            'date'        => date('Y-m-d'),
        ]]);
        $this->assertResponseIsSuccessful();

        return \json_decode($response->getContent());
    }

    public function testItShouldUpdateGivenTask()
    {
        $client = $this->createClientWithCredentials();
        $task   = $this->createTask($client);
        $client->request(
            'PUT',
            'api/tasks/' . $task->uuid,
            [
                'json' => [
                    'title'       => 'updated title',
                    'description' => 'updated description',
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
        $connection = self::getContainer()->get('doctrine')->getConnection();
        $result     = $connection->fetchAllAssociative('select * from tasks where uuid = ? limit 1', [$task->uuid])[0];
        $this->assertSame('updated title', $result['title']);
        $this->assertSame('updated description', $result['description']);
        $this->assertSame($task->date, $result['date']);
    }

    public function testItShouldRestrictUpdateOtherUsersTask()
    {
        $client = $this->createClientWithCredentials();
        $task   = $this->createTask($client);
        $token  = $this->getToken(force: true);
        $client = $this->createClientWithCredentials($token);
        $client->request(
            'PUT',
            'api/tasks/' . $task->uuid,
            [
                'json' => [
                    'title'       => 'updated title',
                    'description' => 'updated description',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(403);
    }

    public function testItShouldNotAllowStatusChangeFromPublishedToDraft()
    {
        $client = $this->createClientWithCredentials();
        $task   = $this->createTask($client);
        $client->request(
            'PUT',
            'api/tasks/' . $task->uuid,
            [
                'json' => [
                    'status' => 'completed',
                ]
            ]
        );
        $client->request(
            'PUT',
            'api/tasks/' . $task->uuid,
            [
                'json' => [
                    'status' => 'draft',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(422);
    }
}
