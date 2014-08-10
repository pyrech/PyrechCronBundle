<?php

namespace Pyrech\CronBundle\Tests\Fixtures\src\CommandValidBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotSchedulableCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('test:valid:not-schedulable')
            ->setDescription('This command is not schedulable')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('not schedulable');
    }
}
