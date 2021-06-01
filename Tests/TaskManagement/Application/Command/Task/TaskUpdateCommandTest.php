<?php


use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class TaskUpdateCommandTest extends \PHPUnit\Framework\TestCase
{
    public function test_update_command_can_return_fields()
    {
        $taskId  = \Ramsey\Uuid\Uuid::uuid4();
        $user    = \Ramsey\Uuid\Uuid::uuid4();
        $date    = new DateTimeImmutable();
        $command = new \TaskManagement\Application\Command\Task\TaskUpdateCommand(
            taskId: $taskId->toString(),
            user: $user->toString(),
            title: "title",
            date: $date,
            description: "description",
            status: \TaskManagement\Domain\Task\Status::INCOMPLETE
        );

        $this->assertSame($command->getTaskId(), $taskId->toString());
        $this->assertSame($command->getDate()->getTimestamp(), $date->getTimestamp());
        $this->assertSame($command->getStatus(), \TaskManagement\Domain\Task\Status::INCOMPLETE);
        $this->assertSame($command->getTitle(), 'title');
        $this->assertSame($command->getDescription(), 'description');
        $this->assertSame($command->getUser(), $user->toString());
    }

    public function test_task_update_handler_can_update_task()
    {
        require_once(__DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php');
        $repository  = new \TaskManagement\Domain\Task\stubs\InMemoryRepository();
        $taskService = new \TaskManagement\Domain\Task\TaskService($repository);
        $handler     = new \TaskManagement\Application\Command\Task\TaskUpdateHandler($taskService);
        $taskId      = TaskId::generate();
        $task        = Task::create(
            taskId: $taskId,
            user: User::fromString(\Ramsey\Uuid\Uuid::uuid4()),
            title: Title::fromString("title"),
            description: Description::fromString("description"),
            status: Status::fromInt(Status::INCOMPLETE),
            date: Date::create(new DateTimeImmutable())
        );
        $taskService->store($task);
        $command = new \TaskManagement\Application\Command\Task\TaskUpdateCommand(
            taskId: $taskId->toString(),
            user: \Ramsey\Uuid\Uuid::uuid4(),
            title: "title updated",
            date: new DateTimeImmutable('2000-02-10 16:00:00'),
            description: "description updated",
            status: \TaskManagement\Domain\Task\Status::COMPLETED
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
}