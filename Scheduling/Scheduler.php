<?php

namespace Pyrech\CronBundle\Scheduling;

use Pyrech\CronBundle\Formatter\FormatterInterface;
use Pyrech\CronBundle\Model\TaskInterface;

/**
 * Class Scheduler
 *
 * Main implementation of the SchedulerInterface
 */
class Scheduler implements SchedulerInterface
{
    /** @var  FormatterInterface */
    private $formatter;

    /** @var TaskInterface[] */
    private $tasks = array();

    public function __construct(FormatterInterface $formatter)
    {
        $this->setFormatter($formatter);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function addTask(TaskInterface $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * {@inheritdoc}
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $rows = array();
        foreach ($this->getTasks() as $task) {
            $rows[] = $this->dumper->format($task);
        }

        return $rows;
    }
}
