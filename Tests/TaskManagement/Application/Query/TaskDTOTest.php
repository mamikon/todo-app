<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TaskManagement\Application\Query\TaskDTO;
use TaskManagement\Domain\Task\Status;

class TaskDTOTest extends TestCase
{
    public function testTaskDtoReturnsExpectedResult()
    {
        $user    = Uuid::uuid4()->toString();
        $taskId  = Uuid::uuid4()->toString();
        $date    = '2000-01-01 10:10:10';
        $taskDTO = new TaskDTO(
            taskId: $taskId,
            userId: $user,
            title: 'title',
            description: 'description',
            status: Status::COMPLETED,
            date: $date
        );

        $this->assertSame($user, $taskDTO->getUserId());
        $this->assertSame($date, $taskDTO->getDate());
        $this->assertSame('title', $taskDTO->getTitle());
        $this->assertSame('description', $taskDTO->getDescription());
        $this->assertSame(Status::COMPLETED, $taskDTO->getStatus());
        $this->assertSame($taskId, $taskDTO->getTaskId());
    }
}
