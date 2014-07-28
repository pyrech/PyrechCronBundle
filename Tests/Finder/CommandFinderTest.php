<?php

namespace Pyrech\CronBundle\Tests\Finder;

use Pyrech\CronBundle\Finder\CommandFinder;
use Pyrech\CronBundle\Finder\TaskFinderInterface;
use Pyrech\CronBundle\Scheduling\TaskBuilder;
use Pyrech\CronBundle\Tests\CronBundleTestCase;

class CommandFinderTest extends CronBundleTestCase
{
    /** @var TaskFinderInterface */
    private $finder;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task', $kernel->getRootDir(), array('console'));
        $validator = $kernel->getContainer()->get('validator');

        $this->finder = new CommandFinder($builder, $kernel, $validator);
    }

    public function testFindValidCommands()
    {
        $tasks = $this->finder->find();

        $this->assertCount(2, $tasks);

        $jobs = array_map(function($task) { return $task->getJob(); }, $tasks);

        $this->assertCount(1, preg_grep('#.*php.* .*console test:command$#', $jobs));
        $this->assertCount(1, preg_grep('#.*php.* .*console test:foo#', $jobs));
    }
}
