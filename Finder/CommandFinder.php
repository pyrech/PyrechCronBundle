<?php

namespace Pyrech\CronBundle\Finder;

use Pyrech\CronBundle\Exception\InvalidTaskException;
use Pyrech\CronBundle\Model\TaskInterface;
use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as LegacyValidatorInterface;

class CommandFinder implements TaskFinderInterface
{
    /** @var TaskBuilderInterface  */
    private $builder;

    /** @var KernelInterface  */
    private $kernel;

    /** @var ValidatorInterface|LegacyValidatorInterface */
    private $validator;

    /**
     * @param TaskBuilderInterface                        $builder
     * @param KernelInterface                             $kernel
     * @param ValidatorInterface|LegacyValidatorInterface $validator
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(TaskBuilderInterface $builder, KernelInterface $kernel, $validator)
    {
        if (! $validator instanceof ValidatorInterface && ! $validator instanceof LegacyValidatorInterface) {
            throw new \InvalidArgumentException('The validator should implement Symfony\Component\Validator\Validator\ValidatorInterface');
        }
        $this->builder = $builder;
        $this->kernel = $kernel;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function find()
    {
        $tasks = array();
        $application = new RegistrableCommandApplication($this->kernel);

        $application->doRegisterCommands();

        foreach ($application->all() as $command) {
            if ($command instanceof SchedulableInterface) {
                $tasks[] = $this->createTask($command);
            }
        }

        return $tasks;
    }

    /**
     * @param SchedulableInterface $command
     *
     * @throws InvalidTaskException if the task is not valid
     *
     * @return TaskInterface
     */
    private function createTask(SchedulableInterface $command)
    {
        $this->builder->reset();

        $command->configTask($this->builder);
        $task = $this->builder->getTask();

        $violations = $this->validator->validate($task);

        if ($violations->count() > 0) {
            throw new InvalidTaskException($task, $violations);
        }

        return $task;
    }
}

/**
 * Class RegisterableCommandApplication
 *
 * Internal class used to access the protected Application::registerCommands method
 */
class RegistrableCommandApplication extends Application {
    public function doRegisterCommands() {
        $this->registerCommands();
    }
}
