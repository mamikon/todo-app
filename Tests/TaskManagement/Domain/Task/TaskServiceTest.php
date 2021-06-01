<?php


class TaskServiceTest extends \PHPUnit\Framework\TestCase
{
    public function test_it_must_store_user_task()
    {
        require_once(__DIR__ . '/stubs/InMemoryRepository.php');
        $repository  = new \TaskManagement\Domain\Task\stubs\InMemoryRepository();
        $taskService = new \TaskManagement\Domain\Task\TaskService($repository);
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
        $taskService->store($task);

        $this->assertSame($taskService->getById($taskId)->getTaskId()->toString(), $taskId->toString());

    }
}