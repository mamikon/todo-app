<?php

use PHPUnit\Framework\TestCase;
use TaskManagement\Domain\Task\Exception\EmptyArgumentException;
use TaskManagement\Domain\Task\Title;

class TitleTest extends TestCase
{
    public function testThatTitleCanBeCreatedFromString()
    {
        $titleString = 'Test';
        $title       = Title::fromString($titleString);
        $this->assertSame($titleString, $title->toString());
    }

    public function testThatEmptyTitleWillThrowException()
    {
        $this->expectException(EmptyArgumentException::class);
        Title::fromString('');
    }

    public function testTitleMustBeCreatedViaNamedConstructor()
    {
        $this->expectException(\Error::class);
        new Title('test');
    }
}
