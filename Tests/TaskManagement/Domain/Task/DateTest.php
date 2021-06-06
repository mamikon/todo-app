<?php

use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    public function testDateCanBeCreatedFromDateTimeImmutableAndOnlyViaNamedConstructor()
    {
        $dateTime = new DateTimeImmutable();
        $format   = 'Y-m-d';
        $date     = \TaskManagement\Domain\Task\Date::create($dateTime);
        $this->assertSame($dateTime->format($format), $date->toString($format));
        $this->expectException(\Error::class);
        $date = new \TaskManagement\Domain\Task\Date($dateTime);
    }
}
