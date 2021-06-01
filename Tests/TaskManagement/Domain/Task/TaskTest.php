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

        $task = \TaskManagement\Domain\Task\Task::create(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status
        );

        $this->assertSame($taskId, $task->getTaskId());
        $this->assertSame($user, $task->getUser());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($description, $task->getDescription());
        $this->assertSame($status, $task->getStatus());

        $this->expectException(\Error::class);
        $task = new TaskManagement\Domain\Task\Task(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status
        );

    }
}