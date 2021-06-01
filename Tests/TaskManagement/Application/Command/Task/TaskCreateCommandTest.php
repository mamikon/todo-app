<?php


class TaskCreateCommandTest extends \PHPUnit\Framework\TestCase
{
    public function test_command_can_return_fields()
    {
        $user    = \Ramsey\Uuid\Uuid::uuid4();
        $date    = new DateTimeImmutable();
        $command = new \TaskManagement\Application\Command\Task\TaskCreateCommand(
            user: $user->toString(),
            title: "title",
            date: $date,
            description: "description",
            status: \TaskManagement\Domain\Task\Status::INCOMPLETE
        );

        $this->assertSame($command->getDate()->getTimestamp(), $date->getTimestamp());
        $this->assertSame($command->getStatus(), \TaskManagement\Domain\Task\Status::INCOMPLETE);
        $this->assertSame($command->getTitle(), 'title');
        $this->assertSame($command->getDescription(), 'description');
        $this->assertSame($command->getUser(), $user->toString());
    }
}