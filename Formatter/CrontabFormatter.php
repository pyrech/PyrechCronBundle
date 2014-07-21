<?php

namespace Pyrech\CronBundle\Formatter;

use Pyrech\CronBundle\Model\TaskInterface;
use Pyrech\CronBundle\Util\Operator;

/**
 * Class CrontabFormatter
 *
 * This formatter is planned to be used on Unix systems.
 * It transforms a task in the crontab format (format used in this bundle)
 */
class CrontabFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(TaskInterface $task)
    {
        $result = sprintf('%s %s %s %s %s %s',
            $this->formatMinute($task),
            $this->formatHour($task),
            $this->formatDay($task),
            $this->formatMonth($task),
            $this->formatDayOfTheWeek($task),
            $task->getJob()
        );

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function formatMinute(TaskInterface $task)
    {
        return $this->formatTimePart($task->getMinute());
    }

    /**
     * {@inheritdoc}
     */
    public function formatHour(TaskInterface $task)
    {
        return $this->formatTimePart($task->getHour());
    }

    /**
     * {@inheritdoc}
     */
    public function formatDay(TaskInterface $task)
    {
        return $this->formatTimePart($task->getDay());
    }

    /**
     * {@inheritdoc}
     */
    public function formatMonth(TaskInterface $task)
    {
        return $this->formatTimePart($task->getMonth());
    }

    /**
     * {@inheritdoc}
     */
    public function formatDayOfTheWeek(TaskInterface $task)
    {
        return $this->formatTimePart($task->getDayOfTheWeek());
    }

    /**
     * Transform a time part into the correct crontab string part
     *
     * @param mixed $timePart
     *
     * @return string
     */
    protected function formatTimePart($timePart)
    {
        if (null === $timePart) {
            return Operator::ASTERIX;
        }

        return (string) $timePart;
    }
}
