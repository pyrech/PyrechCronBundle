<?php

namespace Pyrech\CronBundle\Tests\Fixtures\src\CommandValidBundle\Command;

use Pyrech\CronBundle\Scheduling\SchedulableInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BarCommand extends Command implements SchedulableInterface
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('test:valid:bar')
            ->setDescription('foo!')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('I suggest a new strategy, R2. Let the wookiee win.');
    }

    /**
     * {@inheritdoc}
     */
    public function configTask(TaskBuilderInterface $builder)
    {
        $builder
            ->setCommand($this)
            ->setMonthly(15)
        ;
    }
}
