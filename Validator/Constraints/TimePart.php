<?php

namespace Pyrech\CronBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class TimePart extends Constraint
{
    public $minimum;
    public $maximum;
}
