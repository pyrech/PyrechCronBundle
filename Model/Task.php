<?php

namespace Pyrech\CronBundle\Model;

class Task implements TaskInterface
{
    /** @var mixed */
    private $minute = null;

    /** @var mixed */
    private $hour = null;

    /** @var mixed */
    private $dayOfWeek = null;

    /** @var mixed */
    private $dayOfMonth = null;

    /** @var mixed */
    private $month = null;

    /** @var string */
    private $job = '';

    /** @var bool */
    private $outputDiscarded = true;

    /**
     * @param string $job
     */
    public function __construct($job)
    {
        $this->setJob($job);
    }

    /**
     * {@inheritdoc}
     */
    public function setMinute($minute=null)
    {
        $this->minute = $minute;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * {@inheritdoc}
     */
    public function setHour($hour=null)
    {
        $this->hour = $hour;
    }

    /**
     * {@inheritdoc}
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * {@inheritdoc}
     */
    public function setDayOfWeek($dayOfWeek=null)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * {@inheritdoc}
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * {@inheritdoc}
     */
    public function setDayOfMonth($dayOfMonth=null)
    {
        $this->dayOfMonth = $dayOfMonth;
    }

    /**
     * {@inheritdoc}
     */
    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    /**
     * {@inheritdoc}
     */
    public function setMonth($month=null)
    {
        $this->month = $month;
    }

    /**
     * {@inheritdoc}
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * {@inheritdoc}
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param bool $outputDiscarded
     */
    public function setOutputDiscarded($outputDiscarded=true)
    {
        $this->outputDiscarded = $outputDiscarded;
    }

    /**
     * @return bool
     */
    public function hasOutputDiscarded()
    {
        return $this->outputDiscarded;
    }
}
