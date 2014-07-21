<?php

namespace Pyrech\CronBundle\Model;

class Task implements TaskInterface
{
    /** @var mixed */
    private $minute = null;

    /** @var mixed */
    private $hour = null;

    /** @var mixed */
    private $day = null;

    /** @var mixed */
    private $month = null;

    /** @var mixed */
    private $dayOfTheWeek = null;

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
    public function setDay($day=null)
    {
        $this->day = $day;
    }

    /**
     * {@inheritdoc}
     */
    public function getDay()
    {
        return $this->day;
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
    public function setDayOfTheWeek($dayOfTheWeek=null)
    {
        $this->dayOfTheWeek = $dayOfTheWeek;
    }

    /**
     * {@inheritdoc}
     */
    public function getDayOfTheWeek()
    {
        return $this->dayOfTheWeek;
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
