<?php

namespace Pyrech\CronBundle\Scheduling;

interface SchedulableInterface
{
    public function configTask(TaskBuilder $builder);
}
