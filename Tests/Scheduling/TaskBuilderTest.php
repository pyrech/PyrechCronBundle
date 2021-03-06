<?php

namespace Pyrech\CronBundle\Tests\Scheduling;

use Pyrech\CronBundle\Exception\BuilderException;
use Pyrech\CronBundle\Scheduling\TaskBuilder;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Pyrech\CronBundle\Tests\Fixtures\app\AppKernel;
use Pyrech\CronBundle\Tests\Fixtures\CommandOutsideBundle\SchedulableCommand;

class TaskBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var TaskBuilderInterface */
    protected $taskBuilder;

    protected function setUp()
    {
        $this->taskBuilder = new TaskBuilder('\Pyrech\CronBundle\Model\Task');
    }

    public function testSetTaskClass()
    {
        try {
            $this->taskBuilder->setTaskClass('');
            $this->fail();
        } catch (BuilderException $e) {
            $this->assertSame(
                'The class \'\' doesn\'t exist',
                $e->getMessage()
            );
        }

        try {
            $this->taskBuilder->setTaskClass('\Pyrech\CronBundle\Tests\Fixtures\Task\TaskThatDoesntExist');
            $this->fail();
        } catch (BuilderException $e) {
            $this->assertSame(
                'The class \'\Pyrech\CronBundle\Tests\Fixtures\Task\TaskThatDoesntExist\' doesn\'t exist',
                $e->getMessage()
            );
        }

        try {
            $this->taskBuilder->setTaskClass('\Pyrech\CronBundle\Tests\Fixtures\Task\TaskNotImplementingInterface');
            $this->fail();
        } catch (BuilderException $e) {
            $this->assertSame(
                'The class \'\Pyrech\CronBundle\Tests\Fixtures\Task\TaskNotImplementingInterface\' should implement \Pyrech\CronBundle\Model\TaskInterface',
                $e->getMessage()
            );
        }
    }

    public function testGetTask()
    {
        $this->taskBuilder
            ->setJob('/usr/bin/php -v')
            ->setMinute(1)
            ->setHour(2)
            ->setDayOfWeek(3)
            ->setDayOfMonth(4)
            ->setMonth(5)
            ->setOutputDiscarded(false);

        $task = $this->taskBuilder->getTask();

        $this->assertInstanceOf('\Pyrech\CronBundle\Model\Task', $task);

        $this->assertSame('/usr/bin/php -v', $task->getJob());
        $this->assertSame(1, $task->getMinute());
        $this->assertSame(2, $task->getHour());
        $this->assertSame(3, $task->getDayOfWeek());
        $this->assertSame(4, $task->getDayOfMonth());
        $this->assertSame(5, $task->getMonth());
        $this->assertSame(false, $task->hasOutputDiscarded());
    }

    public function testReset()
    {
        $this->taskBuilder
            ->setJob('/usr/bin/php -v')
            ->setMinute(1)
            ->setHour(2)
            ->setDayOfWeek(5)
            ->setDayOfMonth(3)
            ->setMonth(4)
            ->setOutputDiscarded(false);

        $this->taskBuilder->reset();

        $task = $this->taskBuilder->getTask();

        $this->assertEmpty($task->getJob());
        $this->assertNull($task->getMinute());
        $this->assertNull($task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());
        $this->assertTrue($task->hasOutputDiscarded());
    }

    public function testSetCommand()
    {
        $kernel = new AppKernel('test', false);

        $taskBuilder = new TaskBuilder(
            '\Pyrech\CronBundle\Model\Task',
            $kernel->getRootDir(),
            array('console')
        );

        $command = new SchedulableCommand();
        $command->configTask($taskBuilder);

        $task = $taskBuilder->getTask();

        $this->assertRegExp('#.*php.* .*console test:outside:schedulable#', $task->getJob());
        $this->assertSame(30, $task->getMinute());
        $this->assertSame(12, $task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());
        $this->assertSame(true, $task->hasOutputDiscarded());
    }

    public function testSetCommandWithRootDirOrConsolePath()
    {
        $kernel = new AppKernel('test', false);

        try {
            $taskBuilder = new TaskBuilder(
                '\Pyrech\CronBundle\Model\Task',
                '',
                array('console')
            );

            $command = new SchedulableCommand();
            $command->configTask($taskBuilder);
            $this->fail();
        } catch (BuilderException $e) {
            $this->assertSame(
                'The rootDir and possibleConsolePaths should be set to find the console file',
                $e->getMessage()
            );
        }

        try {
            $taskBuilder = new TaskBuilder(
                '\Pyrech\CronBundle\Model\Task',
                $kernel->getRootDir(),
                array()
            );

            $command = new SchedulableCommand();
            $command->configTask($taskBuilder);
            $this->fail();
        } catch (BuilderException $e) {
            $this->assertSame(
                'The rootDir and possibleConsolePaths should be set to find the console file',
                $e->getMessage()
            );
        }

    }

    public function testSetCommandWithNonExistentConsole()
    {
        try {
            $taskBuilder = new TaskBuilder(
                '\Pyrech\CronBundle\Model\Task',
                DIRECTORY_SEPARATOR.'myapp',
                array('myconsole')
            );

            $command = new SchedulableCommand();
            $command->configTask($taskBuilder);
            $this->fail();
        } catch (BuilderException $e) {
            $this->assertSame(
                'The console bootstrap was not found in the following path ['.DIRECTORY_SEPARATOR.'myapp'.DIRECTORY_SEPARATOR.'myconsole]',
                $e->getMessage()
            );
        }

    }

    public function testSetHourly()
    {
        $this->taskBuilder->setHourly(30);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(30, $task->getMinute());
        $this->assertNull($task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());
    }

    public function testSetDaily()
    {
        $this->taskBuilder->setDaily(12, 30);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(30, $task->getMinute());
        $this->assertSame(12, $task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());
    }

    public function testSetWeekly()
    {
        $this->taskBuilder->setWeekly(0, 3, 15);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(15, $task->getMinute());
        $this->assertSame(3, $task->getHour());
        $this->assertSame(0, $task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());
    }

    public function testSetMonthly()
    {
        $this->taskBuilder->setMonthly(15, 2, 55);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(55, $task->getMinute());
        $this->assertSame(2, $task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertSame(15, $task->getDayOfMonth());
        $this->assertNull($task->getMonth());
    }

    public function testSetYearly()
    {
        $this->taskBuilder->setYearly(12, 15, 4, 30);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(30, $task->getMinute());
        $this->assertSame(4, $task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertSame(15, $task->getDayOfMonth());
        $this->assertSame(12, $task->getMonth());
    }
}
