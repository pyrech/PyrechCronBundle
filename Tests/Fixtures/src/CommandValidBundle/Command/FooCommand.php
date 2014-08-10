<?php

namespace Pyrech\CronBundle\Tests\Fixtures\src\CommandValidBundle\Command;

use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends Command implements SchedulableInterface
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('test:valid:foo')
            ->setDescription('This command looks minimalist')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('The Force is strong with this one.');
    }

    /**
     * {@inheritdoc}
     */
    public function configTask(TaskBuilderInterface $builder)
    {
        $builder
            ->setCommand($this)
            ->setHourly(15)
        ;
    }
}
