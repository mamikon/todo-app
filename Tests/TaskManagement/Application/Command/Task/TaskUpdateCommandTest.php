<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TaskManagement\Application\Command\Task\TaskUpdateCommand;
use TaskManagement\Application\Command\Task\TaskUpdateHandler;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\stubs\InMemoryRepository;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskService;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class TaskUpdateCommandTest extends TestCase
{
    public function testUpdateCommandCanReturnFields()
    {
        $taskId  = Uuid::uuid4();
        $user    = Uuid::uuid4();
        $date    = new DateTimeImmutable();
        $command = new TaskUpdateCommand(
            taskId: $taskId->toString(),
            user: $user->toString(),
            title: 'title',
            date: $date,
            description: 'description',
            status: Status::INCOMPLETE
        );

        $this->assertSame($command->getTaskId(), $taskId->toString());
        $this->assertSame($command->getDate()->getTimestamp(), $date->getTimestamp());
        $this->assertSame($command->getStatus(), Status::INCOMPLETE);
        $this->assertSame($command->getTitle(), 'title');
        $this->assertSame($command->getDescription(), 'description');
        $this->assertSame($command->getUser(), $user->toString());
    }

    public function testTaskUpdateHandlerCanUpdateTask()
    {
        require_once __DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php';
        $repository  = new InMemoryRepository();
        $taskService = new TaskService($repository);
        $handler     = new TaskUpdateHandler($taskService);
        $taskId      = TaskId::generate();
        $task        = Task::create(
            taskId: $taskId,
            user: User::fromString(Uuid::uuid4()),
            title: Title::fromString('title'),
            description: Description::fromString('description'),
            status: Status::fromInt(Status::INCOMPLETE),
            date: Date::create(new DateTimeImmutable())
        );
        $taskService->store($task);
        $command = new TaskUpdateCommand(
            taskId: $taskId->toString(),
            user: Uuid::uuid4(),
            title: 'title updated',
            date: new DateTimeImmutable('2000-02-10 16:00:00'),
            description: 'description updated',
            status: Status::COMPLETED
        );
        $handler($command);
        $updatedTask = $taskService->getById($taskId);
        $this->assertSame($updatedTask->getTaskId()->toString(), $task->getTaskId()->toString());
        $this->assertNotSame($updatedTask->getDate()->toString(), $task->getDate()->toString());
        $this->assertNotSame($updatedTask->getTitle()->toString(), $task->getTitle()->toString());
        $this->assertNotSame($updatedTask->getDescription()->toString(), $task->getDescription()->toString());
        $this->assertNotSame($updatedTask->getUser()->toString(), $task->getUser()->toString());
        $this->assertNotSame($updatedTask->getStatus()->getValue(), $task->getStatus()->getValue());
    }

    public function testTaskUpdateHandlerCanProcessOnlyRequiredUpdates()
    {
        require_once __DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php';
        require_once __DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php';
        $repository  = new InMemoryRepository();
        $taskService = new TaskService($repository);
        $handler     = new TaskUpdateHandler($taskService);
        $taskId      = TaskId::generate();
        $task        = Task::create(
            taskId: $taskId,
            user: User::fromString(Uuid::uuid4()),
            title: Title::fromString('title'),
            description: Description::fromString('description'),
            status: Status::fromInt(Status::INCOMPLETE),
            date: Date::create(new DateTimeImmutable())
        );
        $taskService->store($task);
        $command = new TaskUpdateCommand(
            taskId: $taskId->toString(),
            status: Status::COMPLETED
        );
        $handler($command);
        $updatedTask = $taskService->getById($taskId);
        $this->assertSame($updatedTask->getTaskId()->toString(), $task->getTaskId()->toString());
        $this->assertSame($updatedTask->getDate()->toString(), $task->getDate()->toString());
        $this->assertSame($updatedTask->getTitle()->toString(), $task->getTitle()->toString());
        $this->assertSame($updatedTask->getDescription()->toString(), $task->getDescription()->toString());
        $this->assertSame($updatedTask->getUser()->toString(), $task->getUser()->toString());
        $this->assertNotSame($updatedTask->getStatus()->getValue(), $task->getStatus()->getValue());
    }
}
