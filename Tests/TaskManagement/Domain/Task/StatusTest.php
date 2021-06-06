<?php

namespace TaskManagement\Domain\Task;

use PHPUnit\Framework\TestCase;
use TaskManagement\Domain\Task\Exception\InvalidTaskStatusException;

class StatusTest extends TestCase
{
    public function testTaskCanBeCreatedFromInt()
    {
        $statusArray = Status::getAvailableStatuses();
        $status      = Status::fromInt(current($statusArray));
        $this->assertInstanceOf(Status::class, $status);
    }

    public function testNotDeclaredStatusMustThrowException()
    {
        $statusArray = Status::getAvailableStatuses();
        $randomInt   = \rand(10000, 999999);
        while (in_array($randomInt, $statusArray)) {
            ++$randomInt;
        }
        $this->expectException(InvalidTaskStatusException::class);
        Status::fromInt($randomInt);
    }

    public function testStatusCanValidateChange()
    {
        $status      = Status::fromInt(Status::COMPLETED);
        $draftStatus = Status::fromInt(Status::DRAFT);
        $this->expectException(InvalidTaskStatusException::class);
        $status->check($draftStatus);
    }

    public function testStatusMustBeCreatedViaNamedConstructor()
    {
        $statusArray = Status::getAvailableStatuses();
        $this->expectException(\Error::class);
        new Status(current($statusArray));
    }

    public function testItCanBeCreatedFromString()
    {
        $status = Status::fromLabel(Status::getLabel(Status::COMPLETED));
        $this->assertInstanceOf(Status::class, $status);
    }
}
