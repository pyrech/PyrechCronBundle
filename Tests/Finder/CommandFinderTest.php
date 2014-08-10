<?php

namespace Pyrech\CronBundle\Tests\Finder;

use Pyrech\CronBundle\Finder\CommandFinder;
use Pyrech\CronBundle\Scheduling\TaskBuilder;
use Pyrech\CronBundle\Tests\CronBundleTestCase;

class CommandFinderTest extends CronBundleTestCase
{
    public function testFindValidCommands()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task', $kernel->getRootDir(), array('console'));
        $validator = $kernel->getContainer()->get('validator');

        $finder = new CommandFinder($builder, $kernel, $validator);

        $tasks = $finder->find();

        $this->assertCount(3, $tasks);

        $jobs = array_map(function ($task) { return $task->getJob(); }, $tasks);

        $this->assertCount(1, preg_grep('#.*php.* .*console test:valid:foo#', $jobs));
        $this->assertCount(1, preg_grep('#.*php.* .*console test:valid:bar#', $jobs));
        $this->assertCount(1, preg_grep('#.*php.* .*console test:outside:schedulable#', $jobs));
    }

    public function testFailWithInvalidCommands()
    {
        $this->setExpectedException('Pyrech\CronBundle\Exception\InvalidTaskException');

        $kernel = self::createKernel();
        $kernel->activeInvalidBundle();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task', $kernel->getRootDir(), array('console'));
        $validator = $kernel->getContainer()->get('validator');

        $finder = new CommandFinder($builder, $kernel, $validator);

        $tasks = $finder->find();
    }
}
