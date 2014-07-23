<?php

namespace Pyrech\CronBundle\Scheduling;

/**
 * Interface SchedulableInterface
 *
 * This interface should be implemented mainly by Commands to detect them
 * easily and configure the related task.
 */
interface SchedulableInterface
{
    /**
     * Configure the properties the task should have (frequency, job to run, etc)
     *
     * @param TaskBuilderInterface $builder
     */
    public function configTask(TaskBuilderInterface $builder);
}
