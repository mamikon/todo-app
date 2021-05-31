<?php


class UserTest extends \PHPUnit\Framework\TestCase
{
    public function test_user_can_be_created_from_string()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $user = \TaskManagement\Domain\User::fromString($uuid);
        $this->assertSame($user->toString(), $uuid);
    }

    public function test_user_can_be_created_true_named_constructor()
    {
        $this->expectException(\Error::class);
        $user = new \TaskManagement\Domain\User("test");
    }
}