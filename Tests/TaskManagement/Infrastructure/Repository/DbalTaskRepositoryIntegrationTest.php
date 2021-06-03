<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class DbalTaskRepositoryIntegrationTest extends KernelTestCase
{

    private ?\Doctrine\DBAL\Connection $connection;


    private function generateTask(string $userId, string $title, string $description, int $status, string $date): Task
    {
        $taskId = TaskId::generate();
        return Task::create(
            taskId: $taskId,
            user: User::fromString($userId),
            title: Title::fromString($title),
            description: Description::fromString($description),
            status: Status::fromInt($status),
            date: Date::create(new DateTimeImmutable($date))
        );
    }

    protected function setUp(): void
    {
        $kernel           = self::bootKernel();
        $this->connection = $kernel->getContainer()->get('doctrine')->getConnection();
    }

    public function test_it_should_store_task_in_database()
    {
        $task = $this->generateTask(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            "Title",
            "Description",
            \TaskManagement\Domain\Task\Status::DRAFT,
            "2001-01-01 10:10:10"
        );

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
        $task = $this->generateTask(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            "Title",
            "Description",
            \TaskManagement\Domain\Task\Status::DRAFT,
            "2001-01-01 10:10:10"
        );

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

    public function test_it_should_return_user_tasks_for_given_period()
    {
        $repository = new \TaskManagement\Infrastructure\Repository\DbalTaskRepository($this->connection);
        $user1      = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $user2      = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $date1      = "2000-01-01 10:10:10";
        $date2      = "2001-01-01 10:10:10";
        $taskList[] = $this->generateTask($user1, "title", "description", Status::INCOMPLETE, $date1);
        $taskList[] = $this->generateTask($user1, "title2", "description2", Status::COMPLETED, $date1);
        $taskList[] = $this->generateTask($user2, "title", "description", Status::INCOMPLETE, $date1);
        $taskList[] = $this->generateTask($user1, "title", "description", Status::INCOMPLETE, $date2);

        foreach ($taskList as $task) {
            $repository->store($task);
        }

        $tasks = $repository->getUserTasksForGivenDate(User::fromString($user1), Date::create(new DateTimeImmutable($date1)));
        $this->assertIsArray($tasks);
        $this->assertCount(2, $tasks);
        $this->assertSame($tasks[0]->getUser()->toString(), $user1);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->connection->close();
        $this->connection = null;
    }
}