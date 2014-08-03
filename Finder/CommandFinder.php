<?php

namespace Pyrech\CronBundle\Finder;

use Pyrech\CronBundle\Model\TaskInterface;
use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as LegacyValidatorInterface;

class CommandFinder extends AbstractFinder
{
    /** @var TaskBuilderInterface  */
    private $builder;

    /** @var KernelInterface  */
    private $kernel;

    /**
     * @param TaskBuilderInterface                        $builder
     * @param KernelInterface                             $kernel
     * @param ValidatorInterface|LegacyValidatorInterface $validator
     */
    public function __construct(TaskBuilderInterface $builder, KernelInterface $kernel, $validator)
    {
        parent::__construct($validator);
        $this->builder = $builder;
        $this->kernel = $kernel;
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
     * @return TaskInterface
     */
    private function createTask(SchedulableInterface $command)
    {
        $this->builder->reset();

        $command->configTask($this->builder);
        $task = $this->builder->getTask();

        $this->validateTask($task);

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
