<?php

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Exception\TaskNotFoundException;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;
use TaskManagement\Infrastructure\Repository\DbalTaskRepository;

class DbalTaskRepositoryIntegrationTest extends KernelTestCase
{
    private ?Connection $connection;

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
        $kernel = self::bootKernel();
        $this->connection = $kernel->getContainer()->get('doctrine')->getConnection();
    }

    public function testItShouldStoreTaskInDatabase()
    {
        $task = $this->generateTask(
            Uuid::uuid4()->toString(),
            'Title',
            'Description',
            Status::DRAFT,
            '2001-01-01 10:10:10'
        );

        $dbalTaskRepository = new DbalTaskRepository($this->connection);
        $dbalTaskRepository->store($task);
        $result = $this->connection->fetchAllAssociative(
            'select * from tasks where uuid = ? limit 1',
            [$task->getTaskId()->toString()]
        )[0];
        $this->assertSame($result['uuid'], $task->getTaskId()->toString());
        $this->assertSame($result['user_uuid'], $task->getUser()->toString());
        $this->assertSame($result['title'], $task->getTitle()->toString());
        $this->assertEquals($result['status'], $task->getStatus()->getValue());
        $this->assertSame($result['date'], $task->getDate()->toString());
    }

    public function testItShouldReturnTaskByUuid()
    {
        $task = $this->generateTask(
            Uuid::uuid4()->toString(),
            'Title',
            'Description',
            Status::DRAFT,
            '2001-01-01 10:10:10'
        );

        $dbalTaskRepository = new DbalTaskRepository($this->connection);
        $dbalTaskRepository->store($task);
        $result = $dbalTaskRepository->getById($task->getTaskId());
        $this->assertSame($task->getTaskId()->toString(), $result->getTaskId()->toString());
        $this->assertSame($task->getTitle()->toString(), $result->getTitle()->toString());
        $this->assertSame($task->getDescription()->toString(), $result->getDescription()->toString());
        $this->assertSame($task->getStatus()->getValue(), $result->getStatus()->getValue());
        $this->expectException(TaskNotFoundException::class);
        $dbalTaskRepository->getById(TaskId::generate());
    }

    public function testItShouldReturnUserTasksForGivenPeriod()
    {
        $repository = new DbalTaskRepository($this->connection);
        $user1      = Uuid::uuid4()->toString();
        $user2      = Uuid::uuid4()->toString();
        $date1      = '2000-01-01 10:10:10';
        $date2      = '2001-01-01 10:10:10';
        $taskList[] = $this->generateTask($user1, 'title', 'description', Status::INCOMPLETE, $date1);
        $taskList[] = $this->generateTask($user1, 'title2', 'description2', Status::COMPLETED, $date1);
        $taskList[] = $this->generateTask($user2, 'title', 'description', Status::INCOMPLETE, $date1);
        $taskList[] = $this->generateTask($user1, 'title', 'description', Status::INCOMPLETE, $date2);

        foreach ($taskList as $task) {
            $repository->store($task);
        }

        $tasks = $repository->getUserTasksForGivenDate(
            User::fromString($user1),
            Date::create(new DateTimeImmutable($date1))
        );
        $this->assertIsArray($tasks);
        $this->assertCount(2, $tasks);
        $this->assertSame($tasks[0]->getUser()->toString(), $user1);
    }

    public function testItShouldUpdateTask()
    {
        $task = $this->generateTask(
            Uuid::uuid4()->toString(),
            'Title',
            'Description',
            Status::DRAFT,
            '2001-01-01 10:10:10'
        );

        $dbalTaskRepository = new DbalTaskRepository($this->connection);
        $dbalTaskRepository->store($task);
        $task->setStatus(Status::fromInt(Status::COMPLETED));
        $dbalTaskRepository->update($task);

        $result = $dbalTaskRepository->getById($task->getTaskId());

        $this->assertSame(Status::COMPLETED, $result->getStatus()->getValue());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->connection->close();
        $this->connection = null;
    }
}
