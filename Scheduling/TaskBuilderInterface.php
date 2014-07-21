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
     */
    public function setTaskClass($taskClass);

    /**
     * @return TaskInterface
     */
    public function getTask();

    /**
     * Reset properties that will be set to the task.
     * Should be called before building a new Task
     */
    public function reset();

    /**
     * @param mixed $minute
     */
    public function setMinute($minute=null);

    /**
     * @param mixed $hour
     */
    public function setHour($hour=null);

    /**
     * @param mixed $day
     */
    public function setDay($day=null);

    /**
     * @param mixed $month
     */
    public function setMonth($month=null);

    /**
     * @param mixed $dayOfTheWeek
     */
    public function setDayOfTheWeek($dayOfTheWeek=null);

    /**
     * @param string $job
     */
    public function setJob($job);

    /**
     * @param Command $command
     */
    public function setCommand(Command $command);

    /**
     * @param bool $outputDiscarded
     */
    public function setOutputDiscarded($outputDiscarded=true);
}
