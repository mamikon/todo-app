<?php

use PHPUnit\Framework\TestCase;
use TaskManagement\Domain\Task\Date;

class DateTest extends TestCase
{
    public function testDateCanBeCreatedFromDateTimeImmutableAndOnlyViaNamedConstructor()
    {
        $dateTime = new DateTimeImmutable();
        $format   = 'Y-m-d';
        $date     = Date::create($dateTime);
        $this->assertSame($dateTime->format($format), $date->toString($format));
        $this->expectException(\Error::class);
        new Date($dateTime);
    }
}
