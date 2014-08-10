<?php

namespace Pyrech\CronBundle\Finder;

use Pyrech\CronBundle\Exception\InvalidTaskException;
use Pyrech\CronBundle\Model\TaskInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as LegacyValidatorInterface;

abstract class AbstractFinder implements TaskFinderInterface
{
    /** @var ValidatorInterface|LegacyValidatorInterface */
    private $validator;

    /**
     * @param ValidatorInterface|LegacyValidatorInterface $validator
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($validator)
    {
        if (! $validator instanceof ValidatorInterface && ! $validator instanceof LegacyValidatorInterface) {
            throw new \InvalidArgumentException('The validator should implement Symfony\Component\Validator\Validator\ValidatorInterface');
        }
        $this->validator = $validator;
    }

    /**
     * @param TaskInterface $task
     *
     * @throws InvalidTaskException if the task is not valid
     */
    protected function validateTask(TaskInterface $task)
    {
        $violations = $this->validator->validate($task);

        if ($violations->count() > 0) {
            throw new InvalidTaskException($task, $violations);
        }
    }
}
