<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Exception\InvalidTaskStatusException;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class TaskTest extends TestCase
{
    public function testTaskCanBeCreated()
    {
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

        $this->assertSame($taskId, $task->getTaskId());
        $this->assertSame($user, $task->getUser());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($description, $task->getDescription());
        $this->assertSame($status, $task->getStatus());
        $this->assertSame($date, $task->getDate());

        $this->expectException(\Error::class);
        new TaskManagement\Domain\Task\Task(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
    }

    public function testSettersCanBeUsed()
    {
        $taskId             = TaskId::generate();
        $user               = User::fromString(Uuid::uuid4());
        $title              = Title::fromString('title');
        $description        = Description::fromString('Description');
        $status             = Status::fromInt(Status::DRAFT);
        $date               = Date::create(new DateTimeImmutable());
        $task               = Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        $userUpdated        = User::fromString(Uuid::uuid4());
        $titleUpdated       = Title::fromString('title updated');
        $descriptionUpdated = Description::fromString('Description updated');
        $statusUpdated      = Status::fromInt(Status::INCOMPLETE);
        $dateUpdated        = Date::create(new DateTimeImmutable());
        $task->setUser($userUpdated);
        $task->setTitle($titleUpdated);
        $task->setDescription($descriptionUpdated);
        $task->setStatus($statusUpdated);
        $task->setDate($dateUpdated);
        $this->assertSame($task->getUser()->toString(), $userUpdated->toString());
        $this->assertSame($task->getTitle()->toString(), $titleUpdated->toString());
        $this->assertSame($task->getDescription()->toString(), $descriptionUpdated->toString());
        $this->assertSame($task->getStatus()->getValue(), $statusUpdated->getValue());
        $this->assertSame($task->getDate()->toString('m-d-Y H:i:s.u'), $dateUpdated->toString('m-d-Y H:i:s.u'));
    }

    public function testStatusChangeIsValidated()
    {
        $taskId        = TaskId::generate();
        $user          = User::fromString(Uuid::uuid4());
        $title         = Title::fromString('title');
        $description   = Description::fromString('Description');
        $status        = Status::fromInt(Status::COMPLETED);
        $date          = Date::create(new DateTimeImmutable());
        $task          = Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        $statusUpdated = Status::fromInt(Status::DRAFT);

        $this->expectException(InvalidTaskStatusException::class);
        $task->setStatus($statusUpdated);
    }
}
