<?php


class DescriptionTest extends \PHPUnit\Framework\TestCase
{
    public function test_that_description_can_be_created_from_string()
    {
        $description = \TaskManagement\Domain\Task\Description::fromString("test");
        $this->assertSame("test", $description->toString());
    }

    public function test_that_description_must_be_created_via_named_constructor()
    {
        $this->expectException(\Error::class);
        $description = new \TaskManagement\Domain\Task\Description("test");
    }
}