<?php

namespace Pyrech\CronBundle\Scheduling;

use Pyrech\CronBundle\Model\TaskInterface;
use Symfony\Component\Console\Command\Command;

interface TaskBuilderInterface
{
    /**
     * @param string $taskClass
     *
     * @throws \Exception if the class doesn't exist
     * @throws \Exception if the class doesn't implement the TaskInterface
     *
     * @return TaskBuilderInterface
     */
    public function setTaskClass($taskClass);

    /**
     * @return TaskInterface
     */
    public function getTask();

    /**
     * Reset properties that will be set to the task.
     * Should be called before building a new Task
     *
     * @return TaskBuilderInterface
     */
    public function reset();

    /**
     * @param mixed $minute
     *
     * @return TaskBuilderInterface
     */
    public function setMinute($minute=null);

    /**
     * @param mixed $hour
     *
     * @return TaskBuilderInterface
     */
    public function setHour($hour=null);

    /**
     * @param mixed $dayOfWeek
     *
     * @return TaskBuilderInterface
     */
    public function setDayOfWeek($dayOfWeek=null);

    /**
     * @param mixed $dayOfMonth
     *
     * @return TaskBuilderInterface
     */
    public function setDayOfMonth($dayOfMonth=null);

    /**
     * @param mixed $month
     *
     * @return TaskBuilderInterface
     */
    public function setMonth($month=null);

    /**
     * @param string $job
     *
     * @return TaskBuilderInterface
     */
    public function setJob($job);

    /**
     * @param Command $command
     *
     * @return TaskBuilderInterface
     */
    public function setCommand(Command $command);

    /**
     * @param bool $outputDiscarded
     *
     * @return TaskBuilderInterface
     */
    public function setOutputDiscarded($outputDiscarded=true);

    /**
     * @param int $minute
     *
     * @return TaskBuilderInterface
     */
    public function setHourly($minute=0);

    /**
     * @param int $hour
     * @param int $minute
     *
     * @return TaskBuilderInterface
     */
    public function setDaily($hour=0, $minute=0);

    /**
     * @param int $dayOfTheWeek
     * @param int $hour
     * @param int $minute
     *
     * @return TaskBuilderInterface
     */
    public function setWeekly($dayOfTheWeek=0, $hour=0, $minute=0);

    /**
     * @param int $day
     * @param int $hour
     * @param int $minute
     *
     * @return TaskBuilderInterface
     */
    public function setMonthly($day=1, $hour=0, $minute=0);

    /**
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     *
     * @return TaskBuilderInterface
     */
    public function setYearly($month=1, $day=1, $hour=0, $minute=0);
}
