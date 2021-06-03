<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbalTaskRepositoryIntegrationTest extends KernelTestCase
{

    private ?\Doctrine\DBAL\Connection $connection;

    /**
     * @return \TaskManagement\Domain\Task\Task
     */
    public function createTask(): \TaskManagement\Domain\Task\Task
    {
        $taskId      = \TaskManagement\Domain\Task\TaskId::generate();
        $user        = \TaskManagement\Domain\Task\User::fromString(\Ramsey\Uuid\Uuid::uuid4());
        $title       = \TaskManagement\Domain\Task\Title::fromString("title");
        $description = \TaskManagement\Domain\Task\Description::fromString("Description");
        $status      = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::DRAFT);
        $date        = \TaskManagement\Domain\Task\Date::create(new DateTimeImmutable());

        $task = \TaskManagement\Domain\Task\Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        return $task;
    }

    protected function setUp(): void
    {
        $kernel           = self::bootKernel();
        $this->connection = $kernel->getContainer()->get('doctrine')->getConnection();
    }

    public function test_it_should_store_task_in_database()
    {
        $task = $this->createTask();

        $dbalTaskRepository = new \TaskManagement\Infrastructure\Repository\DbalTaskRepository($this->connection);
        $dbalTaskRepository->store($task);
        $result = $this->connection->fetchAllAssociative("select * from tasks where uuid = ? limit 1", [$task->getTaskId()->toString()])[0];
        $this->assertSame($result['uuid'], $task->getTaskId()->toString());
        $this->assertSame($result['user_uuid'], $task->getUser()->toString());
        $this->assertSame($result['title'], $task->getTitle()->toString());
        $this->assertEquals($result['status'], $task->getStatus()->getValue());
        $this->assertSame($result['date'], $task->getDate()->toString());

    }

    public function test_it_should_return_task_by_uuid()
    {
        $task               = $this->createTask();
        $dbalTaskRepository = new \TaskManagement\Infrastructure\Repository\DbalTaskRepository($this->connection);
        $dbalTaskRepository->store($task);
        $result = $dbalTaskRepository->getById($task->getTaskId());
        $this->assertSame($task->getTaskId()->toString(), $result->getTaskId()->toString());
        $this->assertSame($task->getTitle()->toString(), $result->getTitle()->toString());
        $this->assertSame($task->getDescription()->toString(), $result->getDescription()->toString());
        $this->assertSame($task->getStatus()->getValue(), $result->getStatus()->getValue());
        $this->expectException(\TaskManagement\Domain\Task\Exception\TaskNotFoundException::class);
        $dbalTaskRepository->getById(\TaskManagement\Domain\Task\TaskId::generate());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->connection->close();
        $this->connection = null;
    }
}