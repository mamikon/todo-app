<?php


class TitleTest extends \PHPUnit\Framework\TestCase
{
    public function test_that_title_can_be_created_from_string()
    {
        $titleString = "Test";
        $title       = \TaskManagement\Domain\Task\Title::fromString($titleString);
        $this->assertSame($titleString, $title->toString());
    }

}