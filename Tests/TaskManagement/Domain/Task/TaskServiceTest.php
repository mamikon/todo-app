<?php

use Ramsey\Uuid\Uuid;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\stubs\InMemoryRepository;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskService;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class TaskServiceTest extends \PHPUnit\Framework\TestCase
{
    public function testItMustStoreUserTask()
    {
        require_once __DIR__ . '/stubs/InMemoryRepository.php';
        $repository  = new InMemoryRepository();
        $taskService = new TaskService($repository);
        $taskId      = TaskId::generate();
        $user        = User::fromString(Uuid::uuid4());
        $title       = Title::fromString('title');
        $description = Description::fromString('Description');
        $status      = Status::fromInt(Status::DRAFT);
        $date        = Date::create(new DateTimeImmutable());

        $task = Task::create(
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

    public function testEntityCanBeUpdated()
    {
        require_once __DIR__ . '/stubs/InMemoryRepository.php';
        $repository  = new InMemoryRepository();
        $taskService = new TaskService($repository);
        $taskId      = TaskId::generate();
        $user        = User::fromString(Uuid::uuid4());
        $title       = Title::fromString('title');
        $description = Description::fromString('Description');
        $status      = Status::fromInt(Status::DRAFT);
        $date        = Date::create(new DateTimeImmutable());

        $task = Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        $taskService->store($task);

        $userNew        = User::fromString(Uuid::uuid4());
        $titleNew       = Title::fromString('title');
        $descriptionNew = Description::fromString('Description');
        $statusNew      = Status::fromInt(Status::DRAFT);
        $dateNew        = Date::create(new DateTimeImmutable('2000-02-10 16:00:00'));

        $task->setStatus($statusNew);
        $task->setDate($dateNew);
        $task->setTitle($titleNew);
        $task->setDescription($descriptionNew);
        $task->setUser($userNew);
        $taskService->update($task);
        $updatedTask = $taskService->getById($taskId);
        $this->assertSame($updatedTask->getUser()->toString(), $userNew->toString());
        $this->assertSame($updatedTask->getTitle()->toString(), $titleNew->toString());
        $this->assertSame($updatedTask->getDescription()->toString(), $descriptionNew->toString());
        $this->assertSame($updatedTask->getStatus()->getValue(), $statusNew->getValue());
        $this->assertSame($updatedTask->getDate()->toString('m-d-Y H:i:s.u'), $dateNew->toString('m-d-Y H:i:s.u'));
    }
}
