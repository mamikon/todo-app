<?php


use TaskManagement\Domain\TaskId;

class TaskIdTest extends \PHPUnit\Framework\TestCase
{
    public function test_task_id_can_be_generated()
    {
        $taskId = \TaskManagement\Domain\TaskId::generate();
        $this->assertTrue(\Ramsey\Uuid\Uuid::isValid($taskId->toString()));
    }

    public function test_task_id_must_be_created_thue_named_constructor()
    {
        $this->expectException(\Error::class);
        $taskId = new TaskId("test");
    }
}