<?php

namespace Pyrech\CronBundle\Formatter;

use Pyrech\CronBundle\Model\TaskInterface;

/**
 * Interface FormatterInterface
 *
 * A dumper is responsible to transform a task into the correct format
 */
interface FormatterInterface
{
    /**
     * @param TaskInterface $task
     *
     * @return string
     */
    public function format(TaskInterface $task);

    /**
     * Format the minute part of the task
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    public function formatMinute(TaskInterface $task);

    /**
     * Format the hour part of the task
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    public function formatHour(TaskInterface $task);

    /**
     * Format the day part of the task
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    public function formatDay(TaskInterface $task);

    /**
     * Format the month part of the task
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    public function formatMonth(TaskInterface $task);

    /**
     * Format the day of the week part of the task
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    public function formatDayOfTheWeek(TaskInterface $task);
}
