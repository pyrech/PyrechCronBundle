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
     * @param mixed $dayOfTheWeek
     */
    public function setDayOfWeek($dayOfTheWeek=null);

    /**
     * @return mixed
     */
    public function getDayOfWeek();

    /**
     * @param mixed $dayOfMonth
     */
    public function setDayOfMonth($dayOfMonth=null);

    /**
     * @return mixed
     */
    public function getDayOfMonth();

    /**
     * @param mixed $month
     */
    public function setMonth($month=null);

    /**
     * @return mixed
     */
    public function getMonth();

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
