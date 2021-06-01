<?php


use TaskManagement\Domain\Task\TaskId;

class TaskIdTest extends \PHPUnit\Framework\TestCase
{
    public function test_task_id_can_be_generated()
    {
        $taskId = \TaskManagement\Domain\Task\TaskId::generate();
        $this->assertTrue(\Ramsey\Uuid\Uuid::isValid($taskId->toString()));
    }

    public function test_task_id_must_be_created_via_named_constructor()
    {
        $this->expectException(\Error::class);
        $taskId = new TaskId("test");
    }

    public function test_if_wrong_uuid_provided_it_will_throw_exception()
    {
        $this->expectException(\TaskManagement\Domain\Task\Exception\InvalidUuidException::class);
        $taskId = \TaskManagement\Domain\Task\TaskId::fromString("invalid");
    }
}