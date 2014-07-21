<?php

namespace Pyrech\CronBundle\Scheduling;

use Pyrech\CronBundle\Formatter\FormatterInterface;
use Pyrech\CronBundle\Model\TaskInterface;

/**
 * Interface SchedulerInterface
 *
 * The scheduler is responsible to store many tasks
 * and export them using a formatter
 */
interface SchedulerInterface
{
    /**
     * Set the formatter which will be used during the export
     *
     * @param FormatterInterface $formatter
     */
    public function setFormatter(FormatterInterface $formatter);

    /**
     * Add a new task to schedule
     *
     * @param TaskInterface $task
     */
    public function addTask(TaskInterface $task);

    /**
     * Return the scheduled tasks
     *
     * @return TaskInterface[]
     */
    public function getTasks();

    /**
     * Export the scheduled tasks in a ready-to-use format (crontab for example)
     *
     * @return string[]
     */
    public function export();
}
