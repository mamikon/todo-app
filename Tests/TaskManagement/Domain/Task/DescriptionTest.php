<?php

use PHPUnit\Framework\TestCase;
use TaskManagement\Domain\Task\Description;

class DescriptionTest extends TestCase
{
    public function testThatDescriptionCanBeCreatedFromString()
    {
        $description = Description::fromString('test');
        $this->assertSame('test', $description->toString());
    }

    public function testThatDescriptionMustBeCreatedViaNamedConstructor()
    {
        $this->expectException(\Error::class);
        new Description('test');
    }
}
