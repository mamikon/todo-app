<?php


namespace TaskManagement\Domain\Task;


use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test_task_can_be_created_from_int()
    {
        $status = \TaskManagement\Domain\Task\Status::fromInt(\TaskManagement\Domain\Task\Status::INCOMPLITE);
        $this->assertInstanceOf(\TaskManagement\Domain\Task\Status::class, $status);
    }
}