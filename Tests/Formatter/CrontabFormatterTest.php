<?php

namespace Pyrech\CronBundle\Tests\Formatter;

use Pyrech\CronBundle\Formatter\CrontabFormatter;
use Pyrech\CronBundle\Formatter\FormatterInterface;
use Pyrech\CronBundle\Model\Task;

class CrontabFormatterTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormatterInterface */
    protected $formatter;

    protected function setUp()
    {
        $this->formatter = new CrontabFormatter();
    }

    public function testFormatTask()
    {
        $task = new Task('/usr/bin/php -v');
        $task->setMinute();
        $task->setHour();
        $task->setDay();
        $task->setMonth();
        $task->setDayOfTheWeek();

        $expected = '* * * * * /usr/bin/php -v';

        $this->assertSame($expected, $this->formatter->format($task));

        $task = new Task('/usr/bin/php -v');
        $task->setMinute(0);
        $task->setHour(2);
        $task->setDay('*/2');
        $task->setMonth();
        $task->setDayOfTheWeek();

        $expected = '0 2 */2 * * /usr/bin/php -v';

        $this->assertSame($expected, $this->formatter->format($task));
    }

    /**
     * @dataProvider getMinuteParts
     */
    public function testFormatMinute($minute, $expected)
    {
        $task = new Task('/usr/bin/php -v');
        $task->setMinute($minute);

        $this->assertSame($expected, $this->formatter->formatMinute($task));
    }

    /**
     * @dataProvider getHourParts
     */
    public function testFormatHour($hour, $expected)
    {
        $task = new Task('/usr/bin/php -v');
        $task->setHour($hour);

        $this->assertSame($expected, $this->formatter->formatHour($task));
    }

    /**
     * @dataProvider getDayParts
     */
    public function testFormatDay($day, $expected)
    {
        $task = new Task('/usr/bin/php -v');
        $task->setDay($day);

        $this->assertSame($expected, $this->formatter->formatDay($task));
    }

    /**
     * @dataProvider getMonthParts
     */
    public function testFormatMonth($month, $expected)
    {
        $task = new Task('/usr/bin/php -v');
        $task->setMonth($month);

        $this->assertSame($expected, $this->formatter->formatMonth($task));
    }

    /**
     * @dataProvider getDayOfTheWeekParts
     */
    public function testFormatDayOfTheWeek($dayOfTheWeek, $expected)
    {
        $task = new Task('/usr/bin/php -v');
        $task->setDayOfTheWeek($dayOfTheWeek);

        $this->assertSame($expected, $this->formatter->formatDayOfTheWeek($task));
    }

    public function getMinuteParts()
    {
        return array(
            array(null, '*'),
            array('*', '*'),

            array('1', '1'),
            array('30', '30'),
            array('59', '59'),
            array(30, '30'),

            array('1,2', '1,2'),
            array('1,30,59', '1,30,59'),

            array('1-59', '1-59'),

            array('1-59/', '1-59/'),
            array('1-59/2', '1-59/2'),
            array('*/2', '*/2'),
        );
    }

    public function getHourParts()
    {
        return array(
            array(null, '*'),
            array('*', '*'),

            array('0', '0'),
            array('12', '12'),
            array('23', '23'),
            array(12, '12'),

            array('0,1', '0,1'),
            array('0,12,23', '0,12,23'),

            array('0-23', '0-23'),

            array('0-23/', '0-23/'),
            array('0-23/2', '0-23/2'),
            array('*/2', '*/2'),
        );
    }

    public function getDayParts()
    {
        return array(
            array(null, '*'),
            array('*', '*'),

            array('1', '1'),
            array('15', '15'),
            array('31', '31'),
            array(15, '15'),

            array('1,2', '1,2'),
            array('1,15,31', '1,15,31'),

            array('1-31', '1-31'),

            array('1-31/', '1-31/'),
            array('1-31/2', '1-31/2'),
            array('*/2', '*/2'),
        );
    }

    public function getMonthParts()
    {
        return array(
            array(null, '*'),
            array('*', '*'),

            array('1', '1'),
            array('6', '6'),
            array('12', '12'),
            array(6, '6'),

            array('1,2', '1,2'),
            array('1,6,12', '1,6,12'),

            array('1-12', '1-12'),

            array('1-12/', '1-12/'),
            array('1-12/2', '1-12/2'),
            array('*/2', '*/2'),
        );
    }

    public function getDayOfTheWeekParts()
    {
        return array(
            array(null, '*'),
            array('*', '*'),

            array('0', '0'),
            array('3', '3'),
            array('6', '6'),
            array(3, '3'),

            array('0,1', '0,1'),
            array('0,3,6', '0,3,6'),

            array('0-6', '0-6'),

            array('0-6/', '0-6/'),
            array('0-6/2', '0-6/2'),
            array('*/2', '*/2'),
        );
    }
}
