<?php

class DateTest extends \PHPUnit\Framework\TestCase
{
    public function test_date_can_be_created_from_date_time_immutable_and_only_via_named_constructor()
    {
        $dateTime = new DateTimeImmutable();
        $format   = "Y-m-d";
        $date     = \TaskManagement\Domain\Task\Date::create($dateTime);
        $this->assertSame($dateTime->format($format), $date->toString($format));
        $this->expectException(\Error::class);
        $date = new \TaskManagement\Domain\Task\Date($dateTime);
    }
}