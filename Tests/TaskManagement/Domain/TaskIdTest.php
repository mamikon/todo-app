<?php


class TaskIdTest extends \PHPUnit\Framework\TestCase
{
    public function test_task_id_can_be_generated()
    {
        $taskId = \TaskManagement\Domain\TaskId::generate();
        $this->assertTrue(\Ramsey\Uuid\Uuid::isValid($taskId->toString()));
    }
}