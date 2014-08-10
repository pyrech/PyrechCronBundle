<?php

namespace Pyrech\CronBundle\Finder;

use Pyrech\CronBundle\Model\TaskInterface;

/**
 * Interface TaskFinderInterface
 */
interface TaskFinderInterface
{
    /**
     * @return TaskInterface[]
     */
    public function find();
}
