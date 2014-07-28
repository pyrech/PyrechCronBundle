<?php

namespace Pyrech\CronBundle\Exception;

use Pyrech\CronBundle\Model\TaskInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidTaskException extends Exception
{
    public function __construct(TaskInterface $task, ConstraintViolationListInterface $violations)
    {
        $strViolations = array();

        foreach ($violations as $violation) {
            $strViolations[] = $violation->getMessage();
        }

        parent::__construct(
            sprintf('Invalid task [%s]', join(', ', $strViolations))
        );
    }
}
