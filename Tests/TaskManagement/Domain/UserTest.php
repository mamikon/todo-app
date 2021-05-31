<?php


class UserTest extends \PHPUnit\Framework\TestCase
{
    public function test_user_can_be_created_from_string()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $user = \TaskManagement\Domain\User::fromString($uuid);
        $this->assertSame($user->toString(), $uuid);
    }
}