<?php

namespace Pyrech\CronBundle\Finder;

use Pyrech\CronBundle\Exception\ConfigException;
use Pyrech\CronBundle\Model\TaskInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as LegacyValidatorInterface;

class ConfigFinder extends AbstractFinder
{
    /** @var TaskBuilderInterface */
    private $builder;

    /** @var array */
    private $tasksConfig = array();

    /**
     * @param array                                       $tasksConfig
     * @param TaskBuilderInterface                        $builder
     * @param ValidatorInterface|LegacyValidatorInterface $validator
     */
    public function __construct(array $tasksConfig, TaskBuilderInterface $builder, $validator)
    {
        parent::__construct($validator);
        $this->tasksConfig = $tasksConfig;
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function find()
    {
        $tasks = array();

        foreach ($this->tasksConfig as $taskConfig) {
            $tasks[] = $this->createTask($taskConfig);
        }

        return $tasks;
    }

    /**
     * @param array $taskConfig
     *
     * @throws ConfigException if the config structure is invalid
     *
     * @return TaskInterface
     */
    private function createTask(array $taskConfig)
    {
        if (! (isset($taskConfig['job']) && (isset($taskConfig['frequency']) || isset($taskConfig['when'])))) {
            throw new ConfigException('Invalid config structure');
        }

        $this->builder->reset();

        $this->buildJob($taskConfig['job']);

        if (isset($taskConfig['frequency'])) {
            $this->buildFromFrequency($taskConfig['frequency']);
        } else {
            $this->buildFromWhen($taskConfig['when']);
        }

        $task = $this->builder->getTask();

        $this->validateTask($task);

        return $task;
    }

    /**
     * @param string $job
     *
     * @throws ConfigException if the PHP bin was not found when using '@phpbin'
     */
    private function buildJob($job)
    {
        if (false !== strpos($job, '@phpbin')) {
            $phpFinder = new PhpExecutableFinder();
            $phpBinPath = $phpFinder->find();

            if (! $phpBinPath) {
                throw new ConfigException('The PHP executable was not found');
            }

            $job = str_replace('@phpbin', $phpBinPath, $job);
        }
        $this->builder->setJob($job);
    }

    /**
     * @param string $frequency
     *
     * @throws ConfigException if the frequency is invalid
     */
    private function buildFromFrequency($frequency)
    {
        switch ($frequency) {
            case 'hourly':
                $this->builder->setHourly();
                break;
            case 'daily':
                $this->builder->setDaily();
                break;
            case 'weekly':
                $this->builder->setWeekly();
                break;
            case 'monthly':
                $this->builder->setMonthly();
                break;
            case 'yearly':
                $this->builder->setYearly();
                break;
            default:
                throw new ConfigException(
                    sprintf('Unrecognized frequency value "%s"', $frequency)
                );
        }
    }

    /**
     * @param array $when
     */
    private function buildFromWhen(array $when)
    {
        if (isset($when['minute'])) {
            $this->builder->setMinute($when['minute']);
        }

        if (isset($when['hour'])) {
            $this->builder->setHour($when['hour']);
        }

        if (isset($when['day_of_week'])) {
            $this->builder->setDayOfWeek($when['day_of_week']);
        }

        if (isset($when['day_of_month'])) {
            $this->builder->setDayOfMonth($when['day_of_month']);
        }

        if (isset($when['month'])) {
            $this->builder->setMonth($when['month']);
        }
    }
}
