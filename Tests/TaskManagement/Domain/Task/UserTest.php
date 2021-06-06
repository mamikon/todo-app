<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TaskManagement\Domain\Task\Exception\InvalidUuidException;
use TaskManagement\Domain\Task\User;

class UserTest extends TestCase
{
    public function testUserCanBeCreatedFromString()
    {
        $uuid = Uuid::uuid4()->toString();
        $user = User::fromString($uuid);
        $this->assertSame($user->toString(), $uuid);
    }

    public function testUserMustBeCreatedViaNamedConstructor()
    {
        $this->expectException(\Error::class);
        new User('test');
    }

    public function testIfInvalidUuidProvidedForUserMustThrowException()
    {
        $this->expectException(InvalidUuidException::class);
        User::fromString('invalid-uuid');
    }
}
