<?php


namespace TaskManagement\Domain\Task;


use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test_task_can_be_created_from_int()
    {
        $status = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::INCOMPLETE);
        $this->assertInstanceOf(\TaskManagement\Domain\Task\Status::class, $status);
    }

    public function test_not_declared_status_must_throw_exception()
    {
        $statusArray = \TaskManagement\Domain\Task\Status::getAvailableStatuses();
        $randomInt   = \rand(10000, 999999);
        while (in_array($randomInt, $statusArray)) {
            $randomInt++;
        }
        $this->expectException(\TaskManagement\Domain\Task\Exception\InvalidTaskStatusProvided::class);
    }
}