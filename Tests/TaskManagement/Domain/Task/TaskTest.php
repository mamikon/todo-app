<?php


class TaskTest extends \PHPUnit\Framework\TestCase
{
    public function test_task_can_be_created()
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

        $this->assertSame($taskId, $task->getTaskId());
        $this->assertSame($user, $task->getUser());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($description, $task->getDescription());
        $this->assertSame($status, $task->getStatus());
        $this->assertSame($date, $task->getDate());

        $this->expectException(\Error::class);
        $task = new TaskManagement\Domain\Task\Task(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );

    }

    public function test_setters_can_be_used()
    {
        $taskId             = \TaskManagement\Domain\Task\TaskId::generate();
        $user               = \TaskManagement\Domain\Task\User::fromString(\Ramsey\Uuid\Uuid::uuid4());
        $title              = \TaskManagement\Domain\Task\Title::fromString("title");
        $description        = \TaskManagement\Domain\Task\Description::fromString("Description");
        $status             = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::DRAFT);
        $date               = \TaskManagement\Domain\Task\Date::create(new DateTimeImmutable());
        $task               = \TaskManagement\Domain\Task\Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        $userUpdated        = \TaskManagement\Domain\Task\User::fromString(\Ramsey\Uuid\Uuid::uuid4());
        $titleUpdated       = \TaskManagement\Domain\Task\Title::fromString("title updated");
        $descriptionUpdated = \TaskManagement\Domain\Task\Description::fromString("Description updated");
        $statusUpdated      = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::INCOMPLETE);
        $dateUpdated        = \TaskManagement\Domain\Task\Date::create(new DateTimeImmutable());
        $task->setUser($userUpdated);
        $task->setTitle($titleUpdated);
        $task->setDescription($descriptionUpdated);
        $task->setStatus($statusUpdated);
        $task->setDate($dateUpdated);
        $this->assertSame($task->getUser()->toString(), $userUpdated->toString());
        $this->assertSame($task->getTitle()->toString(), $titleUpdated->toString());
        $this->assertSame($task->getDescription()->toString(), $descriptionUpdated->toString());
        $this->assertSame($task->getStatus()->getValue(), $statusUpdated->getValue());
        $this->assertSame($task->getDate()->toString("m-d-Y H:i:s.u"), $dateUpdated->toString("m-d-Y H:i:s.u"));
    }

    public function test_status_change_is_validated()
    {
        $taskId        = \TaskManagement\Domain\Task\TaskId::generate();
        $user          = \TaskManagement\Domain\Task\User::fromString(\Ramsey\Uuid\Uuid::uuid4());
        $title         = \TaskManagement\Domain\Task\Title::fromString("title");
        $description   = \TaskManagement\Domain\Task\Description::fromString("Description");
        $status        = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::COMPLETED);
        $date          = \TaskManagement\Domain\Task\Date::create(new DateTimeImmutable());
        $task          = \TaskManagement\Domain\Task\Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        $statusUpdated = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::DRAFT);

        $this->expectException(\TaskManagement\Domain\Task\Exception\InvalidTaskStatusException::class);
        $task->setStatus($statusUpdated);
    }
}