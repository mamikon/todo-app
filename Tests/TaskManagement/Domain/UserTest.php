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

    public function test_if_invalid_uuid_provided_for_user_must_throw_exception()
    {
        $this->expectException(\TaskManagement\Domain\Exception\InvalidUuidException::class);
        $user = \TaskManagement\Domain\User::fromString("invalid-uuid");
    }
}