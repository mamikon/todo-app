<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TaskManagement\Domain\Task\Exception\InvalidUuidException;
use TaskManagement\Domain\Task\TaskId;

class TaskIdTest extends TestCase
{
    public function testTaskIdCanBeGenerated()
    {
        $taskId = TaskId::generate();
        $this->assertTrue(Uuid::isValid($taskId->toString()));
    }

    public function testTaskIdMustBeCreatedViaNamedConstructor()
    {
        $this->expectException(\Error::class);
        new TaskId('test');
    }

    public function testIfWrongUuidProvidedItWillThrowException()
    {
        $this->expectException(InvalidUuidException::class);
        $taskId = TaskId::fromString('invalid');
    }
}
