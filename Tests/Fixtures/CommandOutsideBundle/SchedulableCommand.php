<?php

namespace Pyrech\CronBundle\Tests\Fixtures\CommandOutsideBundle;

use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SchedulableCommand extends Command implements SchedulableInterface
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('test:outside:schedulable')
            ->setDescription('Foo!')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Bar!');
    }

    /**
     * {@inheritdoc}
     */
    public function configTask(TaskBuilderInterface $builder)
    {
        $builder
            ->setCommand($this)
            ->setDaily(12, 30)
        ;
    }
}
