<?php


namespace TaskManagement\Domain\Task;


use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test_task_can_be_created_from_int()
    {
        $statusArray = \TaskManagement\Domain\Task\Status::getAvailableStatuses();
        $status      = \TaskManagement\Domain\Task\Status::fromInt(current($statusArray));
        $this->assertInstanceOf(\TaskManagement\Domain\Task\Status::class, $status);
    }

    public function test_not_declared_status_must_throw_exception()
    {
        $statusArray = \TaskManagement\Domain\Task\Status::getAvailableStatuses();
        $randomInt   = \rand(10000, 999999);
        while (in_array($randomInt, $statusArray)) {
            $randomInt++;
        }
        $this->expectException(\TaskManagement\Domain\Task\Exception\InvalidTaskStatusException::class);
        $status = \TaskManagement\Domain\Task\Status::fromInt($randomInt);
    }

    public function test_task_status_change_is_immutable()
    {
        $status    = \TaskManagement\Domain\Task\Status::fromInt(Status::DRAFT);
        $newStatus = $status->change(Status::INCOMPLETE);
        $this->assertNotSame($status->getValue(), $newStatus->getValue());
        $this->assertSame(Status::DRAFT, $status->getValue());
        $this->assertSame(Status::INCOMPLETE, $newStatus->getValue());

    }

    public function test_change_status_must_not_be_allowed_from_completed_to_draft()
    {
        $status      = \TaskManagement\Domain\Task\Status::fromInt(Status::COMPLETED);
        $draftStatus = \TaskManagement\Domain\Task\Status::fromInt(Status::DRAFT);
        $this->expectException(\TaskManagement\Domain\Task\Exception\InvalidTaskStatusException::class);
        $status->change($draftStatus);
    }

    public function test_status_must_be_created_via_named_constructor()
    {
        $statusArray = \TaskManagement\Domain\Task\Status::getAvailableStatuses();
        $this->expectException(\Error::class);
        $status = new Status(current($statusArray));
    }

}