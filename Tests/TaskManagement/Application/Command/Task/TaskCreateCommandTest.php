<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TaskManagement\Application\Command\Task\TaskCreateCommand;
use TaskManagement\Application\Command\Task\TaskCreateHandler;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\stubs\InMemoryRepository;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskService;
use TaskManagement\Domain\Task\User;

class TaskCreateCommandTest extends TestCase
{
    public function testCommandCanReturnFields()
    {
        $user    = Uuid::uuid4();
        $date    = new DateTimeImmutable();
        $command = new TaskCreateCommand(
            user: $user->toString(),
            title: 'title',
            date: $date,
            description: 'description',
            status: Status::INCOMPLETE
        );

        $this->assertSame($command->getDate()->getTimestamp(), $date->getTimestamp());
        $this->assertSame($command->getStatus(), Status::INCOMPLETE);
        $this->assertSame($command->getTitle(), 'title');
        $this->assertSame($command->getDescription(), 'description');
        $this->assertSame($command->getUser(), $user->toString());
    }

    public function testTaskCreateHandlerCanCreateTask()
    {
        require_once __DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php';
        $repository  = new InMemoryRepository();
        $taskService = new TaskService($repository);
        $handler     = new TaskCreateHandler($taskService);
        $user        = Uuid::uuid4();
        $date        = new DateTimeImmutable();
        $command     = new TaskCreateCommand(
            user: $user->toString(),
            title: 'title',
            date: $date,
            description: 'description',
            status: Status::INCOMPLETE
        );
        $handler($command);
        $usersTasks = $repository->getUserTasksForGivenDate(User::fromString($user->toString()), Date::create($date));
        $this->assertIsArray($usersTasks);
        $this->assertCount(1, $usersTasks);
        $task = $usersTasks[0];
        $this->assertSame($task->getUser()->toString(), $user->toString());
        $this->assertSame($task->getTitle()->toString(), 'title');
        $this->assertSame($task->getDescription()->toString(), 'description');
        $this->assertSame($task->getDate()->toString('Y-m-d'), $date->format('Y-m-d'));
    }

    public function testThatTaskCommandCanBeCreatedWithStatusLabel()
    {
        require_once __DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php';
        $repository  = new InMemoryRepository();
        $taskService = new TaskService($repository);
        $handler     = new TaskCreateHandler($taskService);
        $user        = Uuid::uuid4();
        $date        = new DateTimeImmutable();
        $command     = new TaskCreateCommand(
            user: $user->toString(),
            title: 'title',
            date: $date,
            description: 'description',
            status: Status::getLabel(Status::DRAFT)
        );
        $task        = $handler($command);
        $this->assertInstanceOf(Task::class, $task);
    }
}
