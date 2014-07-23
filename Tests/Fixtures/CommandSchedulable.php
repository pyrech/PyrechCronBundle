<?php

namespace Pyrech\CronBundle\Tests\Fixtures;

use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandSchedulable extends Command implements SchedulableInterface
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('test:command')
            ->setDescription('This command looks minimalist')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('May the force be with you, Luke!');
    }

    public function configTask(TaskBuilderInterface $builder)
    {
        $builder
            ->setCommand($this)
            ->setHourly(15);
    }
}
