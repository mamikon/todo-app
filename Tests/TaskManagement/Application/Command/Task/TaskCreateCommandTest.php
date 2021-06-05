<?php


class TaskCreateCommandTest extends \PHPUnit\Framework\TestCase
{
    public function test_command_can_return_fields()
    {
        $user    = \Ramsey\Uuid\Uuid::uuid4();
        $date    = new DateTimeImmutable();
        $command = new \TaskManagement\Application\Command\Task\TaskCreateCommand(
            user: $user->toString(),
            title: "title",
            date: $date,
            description: "description",
            status: \TaskManagement\Domain\Task\Status::INCOMPLETE
        );

        $this->assertSame($command->getDate()->getTimestamp(), $date->getTimestamp());
        $this->assertSame($command->getStatus(), \TaskManagement\Domain\Task\Status::INCOMPLETE);
        $this->assertSame($command->getTitle(), 'title');
        $this->assertSame($command->getDescription(), 'description');
        $this->assertSame($command->getUser(), $user->toString());
    }

    public function test_task_create_handler_can_create_task()
    {
        require_once(__DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php');
        $repository  = new \TaskManagement\Domain\Task\stubs\InMemoryRepository();
        $taskService = new \TaskManagement\Domain\Task\TaskService($repository);
        $handler     = new \TaskManagement\Application\Command\Task\TaskCreateHandler($taskService);
        $user        = \Ramsey\Uuid\Uuid::uuid4();
        $date        = new DateTimeImmutable();
        $command     = new \TaskManagement\Application\Command\Task\TaskCreateCommand(
            user: $user->toString(),
            title: "title",
            date: $date,
            description: "description",
            status: \TaskManagement\Domain\Task\Status::INCOMPLETE
        );
        $handler($command);
        $usersTasks = $repository->getUserTasksForGivenDate(\TaskManagement\Domain\Task\User::fromString($user->toString()), \TaskManagement\Domain\Task\Date::create($date));
        $this->assertIsArray($usersTasks);
        $this->assertCount(1, $usersTasks);
        $task = $usersTasks[0];
        $this->assertSame($task->getUser()->toString(), $user->toString());
        $this->assertSame($task->getTitle()->toString(), "title");
        $this->assertSame($task->getDescription()->toString(), "description");
        $this->assertSame($task->getDate()->toString("Y-m-d"), $date->format("Y-m-d"));
    }

    public function test_that_task_command_can_be_created_with_status_label()
    {
        require_once(__DIR__ . '/../../../Domain/Task/stubs/InMemoryRepository.php');
        $repository  = new \TaskManagement\Domain\Task\stubs\InMemoryRepository();
        $taskService = new \TaskManagement\Domain\Task\TaskService($repository);
        $handler     = new \TaskManagement\Application\Command\Task\TaskCreateHandler($taskService);
        $user        = \Ramsey\Uuid\Uuid::uuid4();
        $date        = new DateTimeImmutable();
        $command     = new \TaskManagement\Application\Command\Task\TaskCreateCommand(
            user: $user->toString(),
            title: "title",
            date: $date,
            description: "description",
            status: \TaskManagement\Domain\Task\Status::getLabel(\TaskManagement\Domain\Task\Status::DRAFT)
        );
        $task        = $handler($command);
        $this->assertInstanceOf(\TaskManagement\Domain\Task\Task::class, $task);

    }
}