<?php

namespace App\Task;

use App\AbstractFunctionalTest;
use TaskManagement\Domain\Task\Status;

class TaskFetchTest extends AbstractFunctionalTest
{
    private function createTask($client, ?string $date = null)
    {
        $response = $client->request('POST', 'api/tasks', ['json' => [
            'title'       => 'test',
            'description' => 'test',
            'status'      => current(Status::getStatusLabels()),
            'date'        => $date ?? date('Y-m-d'),
        ]]);
        $this->assertResponseIsSuccessful();

        return \json_decode($response->getContent());
    }

    public function testItShouldReturnTaskViaUuid()
    {
        $client   = $this->createClientWithCredentials();
        $task     = $this->createTask($client);
        $response = $client->request('GET', 'api/tasks/' . $task->uuid);
        $this->assertResponseIsSuccessful();
        $result = \json_decode($response->getContent());
        $this->assertSame($task->uuid, $result->uuid);
    }

    public function testItCantGetOtherUserTask()
    {
        $client = $this->createClientWithCredentials();
        $task   = $this->createTask($client);
        $token  = $this->getToken(force: true);
        $client = $this->createClientWithCredentials($token);
        $client->request('GET', 'api/tasks/' . $task->uuid);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testItCantGetAllUserTasks()
    {
        $client = $this->createClientWithCredentials();
        $this->createTask($client);
        $this->createTask($client);
        $response = $client->request('GET', 'api/tasks');
        $result   = \json_decode($response->getContent());
        $this->assertCount(2, $result->{'hydra:member'});
    }

    public function testItShouldGetUserTasksForGivenDate()
    {
        $client = $this->createClientWithCredentials();
        $this->createTask($client);
        $token  = $this->getToken(force: true);
        $client = $this->createClientWithCredentials($token);
        $this->createTask($client);
        $this->createTask($client);
        $this->createTask($client, (new \DateTimeImmutable('yesterday'))->format('Y-m-d'));
        $response = $client->request('GET', 'api/tasks', ['query' => ['date' => date('Y-m-d')]]);

        $result = \json_decode($response->getContent());
        $this->assertCount(2, $result->{'hydra:member'});
    }
}
