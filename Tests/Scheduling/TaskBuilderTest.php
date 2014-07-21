<?php

namespace Pyrech\CronBundle\Tests\Scheduling;

use Pyrech\CronBundle\Scheduling\TaskBuilder;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;

class TaskBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var TaskBuilderInterface */
    protected $taskBuilder;

    protected function setUp()
    {
        $this->taskBuilder = new TaskBuilder('\Pyrech\CronBundle\Model\Task');
    }

    public function setTaskClass()
    {
        try {
            $this->taskBuilder->setTaskClass('');
            $this->fail();
        } catch (\Exception $e) {
            $this->assertSame(
                'The class \'\' doesn\'t exist',
                $e->getMessage()
            );
        }

        try {
            $this->setTaskClass('\Pyrech\CronBundle\Tests\Fixtures\Task\TaskThatDoesntExist');
            $this->fail();
        } catch (\Exception $e) {
            $this->assertSame(
                'The class \'\Pyrech\CronBundle\Tests\Fixtures\Task\TaskThatDoesntExist\' doesn\'t exist',
                $e->getMessage()
            );
        }

        try {
            $this->taskBuilder->setTaskClass('\Pyrech\CronBundle\Tests\Fixtures\Task\TaskNotImplementingInterface');
            $this->fail();
        } catch (\Exception $e) {
            $this->assertSame(
                'The class \'\Pyrech\CronBundle\Tests\Fixtures\Task\TaskNotImplementingInterface\' should implement \Pyrech\CronBundle\Model\TaskInterface',
                $e->getMessage()
            );
        }
    }

    public function testGetTask()
    {
        $this->taskBuilder->setJob('/usr/bin/php -v');
        $this->taskBuilder->setMinute(1);
        $this->taskBuilder->setHour(2);
        $this->taskBuilder->setDay(3);
        $this->taskBuilder->setMonth(4);
        $this->taskBuilder->setDayOfTheWeek(5);
        $this->taskBuilder->setOutputDiscarded(false);

        $task = $this->taskBuilder->getTask();

        $this->assertInstanceOf('\Pyrech\CronBundle\Model\Task', $task);

        $this->assertSame('/usr/bin/php -v', $task->getJob());
        $this->assertSame(1, $task->getMinute());
        $this->assertSame(2, $task->getHour());
        $this->assertSame(3, $task->getDay());
        $this->assertSame(4, $task->getMonth());
        $this->assertSame(5, $task->getDayOfTheWeek());
        $this->assertSame(false, $task->hasOutputDiscarded());
    }

    public function testReset()
    {
        $this->taskBuilder->setJob('/usr/bin/php -v');
        $this->taskBuilder->setMinute(1);
        $this->taskBuilder->setHour(2);
        $this->taskBuilder->setDay(3);
        $this->taskBuilder->setMonth(4);
        $this->taskBuilder->setDayOfTheWeek(5);
        $this->taskBuilder->setOutputDiscarded(false);

        $this->taskBuilder->reset();

        $task = $this->taskBuilder->getTask();

        $this->assertEmpty($task->getJob());
        $this->assertNull($task->getMinute());
        $this->assertNull($task->getHour());
        $this->assertNull($task->getDay());
        $this->assertNull($task->getMonth());
        $this->assertNull($task->getDayOfTheWeek());
        $this->assertTrue($task->hasOutputDiscarded());
    }

    public function testSetCommand()
    {
        $this->markTestSkipped('TODO');
    }
}
