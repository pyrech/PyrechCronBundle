<?php

namespace Pyrech\CronBundle\Model;

interface TaskInterface
{
    /**
     * @param mixed $minute
     */
    public function setMinute($minute=null);

    /**
     * @return mixed
     */
    public function getMinute();

    /**
     * @param mixed $hour
     */
    public function setHour($hour=null);

    /**
     * @return mixed
     */
    public function getHour();

    /**
     * @param mixed $day
     */
    public function setDay($day=null);

    /**
     * @return mixed
     */
    public function getDay();

    /**
     * @param mixed $month
     */
    public function setMonth($month=null);

    /**
     * @return mixed
     */
    public function getMonth();

    /**
     * @param mixed $dayOfTheWeek
     */
    public function setDayOfTheWeek($dayOfTheWeek=null);

    /**
     * @return mixed
     */
    public function getDayOfTheWeek();

    /**
     * @param string $job
     */
    public function setJob($job);

    /**
     * @return string
     */
    public function getJob();

    /**
     * @param bool $outputDiscarded
     */
    public function setOutputDiscarded($outputDiscarded=true);

    /**
     * @return bool
     */
    public function hasOutputDiscarded();
}
