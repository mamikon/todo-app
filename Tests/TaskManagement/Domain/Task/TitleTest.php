<?php


class TitleTest extends \PHPUnit\Framework\TestCase
{
    public function test_that_title_can_be_created_from_string()
    {
        $titleString = "Test";
        $title       = \TaskManagement\Domain\Task\Title::fromString($titleString);
        $this->assertSame($titleString, $title->toString());
    }

    public function test_that_empty_title_will_throw_exception()
    {
        $this->expectException(\TaskManagement\Domain\Task\Exception\EmptyArgumentException::class);
        \TaskManagement\Domain\Task\Title::fromString("");
    }

}