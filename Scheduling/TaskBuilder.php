<?php

namespace Pyrech\CronBundle\Scheduling;

use Pyrech\CronBundle\Model\Task;
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
    private $day;

    /** @var mixed */
    private $month;

    /** @var mixed */
    private $dayOfTheWeek;

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
            throw new \Exception(
                sprintf('The class \'%s\' doesn\'t exist', $taskClass)
            );
        }

        $interface = '\Pyrech\CronBundle\Model\TaskInterface';
        $reflection = new \ReflectionClass($taskClass);
        if (!$reflection->implementsInterface($interface)) {
            throw new \Exception(
                sprintf('The class \'%s\' should implement %s', $taskClass, $interface)
            );
        }

        $this->taskClass = $taskClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getTask()
    {
        $task = new Task($this->job);
        $task->setMinute($this->minute);
        $task->setHour($this->hour);
        $task->setDay($this->day);
        $task->setMonth($this->month);
        $task->setDayOfTheWeek($this->dayOfTheWeek);
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
        $this->day = null;
        $this->month = null;
        $this->dayOfTheWeek = null;
        $this->job = '';
        $this->outputDiscarded = true;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinute($minute = null)
    {
        $this->minute = $minute;
    }

    /**
     * {@inheritdoc}
     */
    public function setHour($hour = null)
    {
        $this->hour = $hour;
    }

    /**
     * {@inheritdoc}
     */
    public function setDay($day = null)
    {
        $this->day = $day;
    }

    /**
     * {@inheritdoc}
     */
    public function setMonth($month = null)
    {
        $this->month = $month;
    }

    /**
     * {@inheritdoc}
     */
    public function setDayOfTheWeek($dayOfTheWeek = null)
    {
        $this->dayOfTheWeek = $dayOfTheWeek;
    }

    /**
     * {@inheritdoc}
     */
    public function setJob($job)
    {
        $this->job = $job;
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
    }

    /**
     * @param bool $outputDiscarded
     */
    public function setOutputDiscarded($outputDiscarded = true)
    {
        $this->outputDiscarded = $outputDiscarded;
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
             throw new \Exception('The PHP executable was not found');
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
            throw new \Exception('The rootDir and possibleConsolePaths should be set to find the console file');
        }

        $triedPaths = array();
        foreach ($this->possibleConsolePaths as $possiblePath) {
            $path = $this->rootDir . DIRECTORY_SEPARATOR . $possiblePath;
            if (file_exists($path)) {
                return $path;
            }
            $triedPaths[] = $path;
        }
        throw new \Exception(
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
        $this->day = null;
        $this->month = null;
        $this->dayOfTheWeek = null;
    }

    /**
     * {@inheritdoc}
     */
    public function setDaily($hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->day = null;
        $this->month = null;
        $this->dayOfTheWeek = null;
    }

    /**
     * {@inheritdoc}
     */
    public function setWeekly($dayOfTheWeek = 0, $hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->day = null;
        $this->month = null;
        $this->dayOfTheWeek = $dayOfTheWeek;
    }

    /**
     * {@inheritdoc}
     */
    public function setMonthly($day = 1, $hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->day = $day;
        $this->month = null;
        $this->dayOfTheWeek = null;
    }

    /**
     * {@inheritdoc}
     */
    public function setYearly($month = 1, $day = 1, $hour = 0, $minute = 0)
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->day = $day;
        $this->month = $month;
        $this->dayOfTheWeek = null;
    }
}
