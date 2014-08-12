<?php

namespace Pyrech\CronBundle\Tests\Finder;

use Pyrech\CronBundle\Exception\ConfigException;
use Pyrech\CronBundle\Exception\InvalidTaskException;
use Pyrech\CronBundle\Finder\ConfigFinder;
use Pyrech\CronBundle\Scheduling\TaskBuilder;
use Pyrech\CronBundle\Tests\CronBundleTestCase;

class ConfigFinderTest extends CronBundleTestCase
{
    public function testFindValidConfigs()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task');
        $validator = $kernel->getContainer()->get('validator');

        $configs = array(
            array(
                'job' => 'echo 1',
                'frequency' => 'weekly'
            )
        );

        $finder = new ConfigFinder($configs, $builder, $validator);

        $tasks = $finder->find();

        $this->assertCount(1, $tasks);

        $task = $tasks[0];
        $this->assertSame('echo 1', $task->getJob());
        $this->assertSame(0, $task->getMinute());
        $this->assertSame(0, $task->getHour());
        $this->assertSame(0, $task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());

        $configs = array(
            array(
                'job' => '@phpbin -v',
                'when' => array(
                    'hour' => 12
                )
            )
        );

        $finder = new ConfigFinder($configs, $builder, $validator);

        $tasks = $finder->find();

        $this->assertCount(1, $tasks);

        $task = $tasks[0];
        $this->assertRegExp('#.*php.* -v#', $task->getJob());
        $this->assertNull($task->getMinute());
        $this->assertSame(12, $task->getHour());
        $this->assertNull($task->getDayOfWeek());
        $this->assertNull($task->getDayOfMonth());
        $this->assertNull($task->getMonth());
    }

    public function testFailWithInvalidStructure()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task');
        $validator = $kernel->getContainer()->get('validator');

        try {
            $configs = array(array());

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (ConfigException $e) {
            $this->assertSame(
                'Invalid config structure',
                $e->getMessage()
            );
        }

        try {
            $configs = array(array(
                'job' => 'echo 1'
            ));

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (ConfigException $e) {
            $this->assertSame(
                'Invalid config structure',
                $e->getMessage()
            );
        }

        try {
            $configs = array(array(
                'frequency' => 'weekly'
            ));

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (ConfigException $e) {
            $this->assertSame(
                'Invalid config structure',
                $e->getMessage()
            );
        }

        try {
            $configs = array(array(
                'when' => array()
            ));

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (ConfigException $e) {
            $this->assertSame(
                'Invalid config structure',
                $e->getMessage()
            );
        }
    }

    public function testFailWithInvalidFrequency()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task');
        $validator = $kernel->getContainer()->get('validator');

        try {
            $configs = array(array(
                'job' => 'echo 1',
                'frequency' => 'invalid_frequency'
            ));

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (ConfigException $e) {
            $this->assertSame(
                'Unrecognized frequency value "invalid_frequency"',
                $e->getMessage()
            );
        }
    }

    public function testFailWithInvalidTaskConfiguration()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $builder = new TaskBuilder('Pyrech\CronBundle\Model\Task');
        $validator = $kernel->getContainer()->get('validator');

        try {
            $configs = array(array(
                'job' => '',
                'frequency' => 'weekly'
            ));

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (InvalidTaskException $e) {

        }

        try {
            $configs = array(array(
                'job' => 'echo 1',
                'when' => array(
                    'hour' => -1
                )
            ));

            $finder = new ConfigFinder($configs, $builder, $validator);

            $finder->find();
            $this->fail();
        } catch (InvalidTaskException $e) {

        }
    }
}
