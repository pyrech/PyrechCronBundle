<?php

namespace Pyrech\CronBundle\Scheduling;

use Pyrech\CronBundle\Exception\BuilderException;
use Pyrech\CronBundle\Model\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\PhpExecutableFinder;

class TaskBuilder implements TaskBuilderInterface
{
    /** @var string */
    private $taskClass;

    /** @var string */
    private $rootDir;

    /** @var array */
    private $possibleConsolePaths;

    /** @var mixed */
    private $minute;

    /** @var mixed */
    private $hour;

    /** @var mixed */
    private $dayOfWeek;

    /** @var mixed */
    private $dayOfMonth;

    /** @var mixed */
    private $month;

    /** @var string */
    private $job;

    /** @var bool */
    private $outputDiscarded;

    /**
     * @param string $taskClass
     * @param string $rootDir
     * @param array  $possibleConsolePaths
     */
    public function __construct($taskClass, $rootDir='', array $possibleConsolePaths=array())
    {
        $this->setTaskClass($taskClass);

        $this->rootDir = $rootDir;
        $this->possibleConsolePaths = $possibleConsolePaths;

        $this->reset();
    }

    /**
     * {@inheritdoc}
     */
    public function setTaskClass($taskClass)
    {
        if (empty($taskClass) || !class_exists($taskClass)) {
            throw new BuilderException(
                sprintf('The class \'%s\' doesn\'t exist', $taskClass)
            );
        }

        $interface = '\Pyrech\CronBundle\Model\TaskInterface';
        $reflection = new \ReflectionClass($taskClass);
        if (!$reflection->implementsInterface($interface)) {
            throw new BuilderException(
                sprintf('The class \'%s\' should implement %s', $taskClass, $interface)
            );
        }

        $this->taskClass = $taskClass;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTask()
    {
        $class = $this->taskClass;
        /** @var TaskInterface $task */
        $task = new $class($this->job);
        $task->setMinute($this->minute);
        $task->setHour($this->hour);
        $task->setDayOfWeek($this->dayOfWeek);
        $task->setDayOfMonth($this->dayOfMonth);
        $task->setMonth($this->month);
        $task->setOutputDiscarded($this->outputDiscarded);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->minute = null;
        $this->hour = null;
        $this->dayOfWeek = null;
        $this->dayOfMonth = null;
        $this->month = null;
        $this->job = '';
        $this->outputDiscarded = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinute($minute = null)
    {
        $this->minute = $minute;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setHour($hour = null)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDayOfWeek($dayOfWeek = null)
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDayOfMonth($dayOfMonth = null)
    {
        $this->dayOfMonth = $dayOfMonth;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMonth($month = null)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCommand(Command $command)
    {
        $job = sprintf(
            '%s %s %s',
            $this->findPhpBinPath(),
            $this->findConsolePath(),
            $command->getName()
        );

        $this->job = $job;

        return $this;
    }

    /**
     * {{@inheritdoc}
     */
    public function setOutputDiscarded($outputDiscarded = true)
    {
        $this->outputDiscarded = $outputDiscarded;

        return $this;
    }

    /**
     * Returns the path to the directory where the PHP binary exists
     *
     * @throws \Exception if the PHP executable was not found
     *
     * @return string
     */
     private function findPhpBinPath()
     {
         $phpExecutableFinder = new PhpExecutableFinder();
         $phpBinPath = $phpExecutableFinder->find();

         if (! $phpBinPath) {
             throw new BuilderException('The PHP executable was not found');
         }

         return $phpBinPath;
     }

    /**
     * Returns the path where the console lives
     *
     * @throws \Exception if the rootDir or possibleConsolePaths was not setted in constructor
     * @throws \Exception if the console path was not found
     *
     * @return string
     */
    private function findConsolePath()
    {
        if (empty($this->rootDir) || empty($this->possibleConsolePaths)) {
            throw new BuilderException('The rootDir and possibleConsolePaths should be set to find the console file');
        }

        $triedPaths = array();
        foreach ($this->possibleConsolePaths as $possiblePath) {
            $path = $this->rootDir . DIRECTORY_SEPARATOR . $possiblePath;
            if (file_exists($path)) {
                return $path;
            }
            $triedPaths[] = $path;
        }
        throw new BuilderException(
            sprintf('The console bootstrap was not found in the following path [%s]', implode(', ', $triedPaths))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setHourly($minute = 0)
    {
        $this->minute = $minute;
        $this->hour = null;
        $this->dayOfWeek = null;
        $this->dayOfMonth = null;
        $this->month = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDaily($hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->dayOfWeek = null;
        $this->dayOfMonth = null;
        $this->month = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setWeekly($dayOfWeek = 0, $hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->dayOfWeek = $dayOfWeek;
        $this->dayOfMonth = null;
        $this->month = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMonthly($dayOfMonth = 1, $hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->dayOfWeek = null;
        $this->dayOfMonth = $dayOfMonth;
        $this->month = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setYearly($month = 1, $dayOfMonth = 1, $hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->dayOfWeek = null;
        $this->dayOfMonth = $dayOfMonth;
        $this->month = $month;

        return $this;
    }
}
