<?php

declare(strict_types=1);

namespace Crunz\Tests\Unit\Schedule;

use Crunz\Event;
use Crunz\Exception\TaskNotExistException;
use Crunz\Schedule;
use Crunz\Schedule\ScheduleFactory;
use Crunz\Task\TaskNumber;
use PHPUnit\Framework\TestCase;

final class ScheduleFactoryTest extends TestCase
{
    /** @test */
    public function singleTaskSchedule(): void
    {
        $factory = new ScheduleFactory();

        $event1 = new Event(1, 'php -v');
        $event2 = new Event(2, 'php -v');
        $schedule = new Schedule();
        $schedule->events([$event1, $event2]);

        $schedules = $factory->singleTaskSchedule(TaskNumber::fromString('1'), $schedule);
        /** @var Schedule $firstSchedule */
        $firstSchedule = \reset($schedules);

        $this->assertSame([$event1], $firstSchedule->events());
    }

    /** @test */
    public function singleTask(): void
    {
        $factory = new ScheduleFactory();

        $event1 = new Event(1, 'php -v');
        $event2 = new Event(2, 'php -v');
        $schedule = new Schedule();
        $schedule->events([$event1, $event2]);

        $event = $factory->singleTask(TaskNumber::fromString('1'), $schedule);

        $this->assertSame($event1, $event);
    }

    /** @test */
    public function singleTaskScheduleThrowsExceptionOnWrongTaskNumber(): void
    {
        $factory = new ScheduleFactory();

        $event1 = new Event(1, 'php -v');
        $schedule = new Schedule();
        $schedule->events([$event1]);

        $this->expectException(TaskNotExistException::class);
        $this->expectExceptionMessage("Task with id '2' was not found. Last task id is '1'.");

        $factory->singleTaskSchedule(TaskNumber::fromString('2'), $schedule);
    }

    /** @test */
    public function singleTaskThrowsExceptionOnWrongTaskNumber(): void
    {
        $factory = new ScheduleFactory();

        $event1 = new Event(1, 'php -v');
        $schedule = new Schedule();
        $schedule->events([$event1]);

        $this->expectException(TaskNotExistException::class);
        $this->expectExceptionMessage("Task with id '2' was not found. Last task id is '1'.");

        $factory->singleTask(TaskNumber::fromString('2'), $schedule);
    }
}
