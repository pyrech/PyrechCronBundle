<?php

namespace Pyrech\CronBundle\Tests\Fixtures\src\CommandInvalidBundle\Command;

use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InvalidTaskCommand extends Command implements SchedulableInterface
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('test:invalid:invalid-task')
            ->setDescription('This command badly configures a task with a wrong minute')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('I’ve got a very bad feeling about this.');
    }

    /**
     * {@inheritdoc}
     */
    public function configTask(TaskBuilderInterface $builder)
    {
        $builder
            ->setCommand($this)
            ->setHourly(-1)
        ;
    }
}

