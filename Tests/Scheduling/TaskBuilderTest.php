<?php

namespace Pyrech\CronBundle\Tests\Scheduling;

use Pyrech\CronBundle\Exception\BuilderException;
use Pyrech\CronBundle\Scheduling\TaskBuilder;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Pyrech\CronBundle\Tests\Fixtures\app\AppKernel;
use Pyrech\CronBundle\Tests\Fixtures\CommandSchedulable;

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
            ->setDay(3)
            ->setMonth(4)
            ->setDayOfTheWeek(5)
            ->setOutputDiscarded(false);

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
        $this->taskBuilder
            ->setJob('/usr/bin/php -v')
            ->setMinute(1)
            ->setHour(2)
            ->setDay(3)
            ->setMonth(4)
            ->setDayOfTheWeek(5)
            ->setOutputDiscarded(false);

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
        $kernel = new AppKernel('test', false);

        $taskBuilder = new TaskBuilder(
            '\Pyrech\CronBundle\Model\Task',
            $kernel->getRootDir(),
            array('console')
        );

        $command = new CommandSchedulable();
        $command->configTask($taskBuilder);

        $task = $taskBuilder->getTask();

        $this->assertRegExp('#.*php.* .*console test:command#', $task->getJob());
        $this->assertSame(15, $task->getMinute());
        $this->assertNull($task->getHour());
        $this->assertNull($task->getDay());
        $this->assertNull($task->getMonth());
        $this->assertNull($task->getDayOfTheWeek());
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

            $command = new CommandSchedulable();
            $command->configTask($taskBuilder);
            $this->fail();
        } catch(BuilderException $e) {
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

            $command = new CommandSchedulable();
            $command->configTask($taskBuilder);
            $this->fail();
        } catch(BuilderException $e) {
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

            $command = new CommandSchedulable();
            $command->configTask($taskBuilder);
            $this->fail();
        } catch(BuilderException $e) {
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
        $this->assertNull($task->getDay());
        $this->assertNull($task->getMonth());
        $this->assertNull($task->getDayOfTheWeek());
    }

    public function testSetDaily()
    {
        $this->taskBuilder->setDaily(12, 30);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(30, $task->getMinute());
        $this->assertSame(12, $task->getHour());
        $this->assertNull($task->getDay());
        $this->assertNull($task->getMonth());
        $this->assertNull($task->getDayOfTheWeek());
    }

    public function testSetWeekly()
    {
        $this->taskBuilder->setWeekly(0, 3, 15);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(15, $task->getMinute());
        $this->assertSame(3, $task->getHour());
        $this->assertNull($task->getDay());
        $this->assertNull($task->getMonth());
        $this->assertSame(0, $task->getDayOfTheWeek());
    }

    public function testSetMonthly()
    {
        $this->taskBuilder->setMonthly(15, 2, 55);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(55, $task->getMinute());
        $this->assertSame(2, $task->getHour());
        $this->assertSame(15, $task->getDay());
        $this->assertNull($task->getMonth());
        $this->assertNull($task->getDayOfTheWeek());
    }

    public function testSetYearly()
    {
        $this->taskBuilder->setYearly(12, 15, 4, 30);

        $task = $this->taskBuilder->getTask();

        $this->assertSame(30, $task->getMinute());
        $this->assertSame(4, $task->getHour());
        $this->assertSame(15, $task->getDay());
        $this->assertSame(12, $task->getMonth());
        $this->assertNull($task->getDayOfTheWeek());
    }
}
