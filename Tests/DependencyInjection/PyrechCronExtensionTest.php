<?php

namespace Pyrech\CronBundle\Tests\DependencyInjection;

use Pyrech\CronBundle\DependencyInjection\PyrechCronExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class PyrechCronExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    public function testTasksAreNotRequired()
    {
        $this->createEmptyConfiguration();

        $this->assertNotHasDefinition('pyrech_cron.tasks');
    }

    public function testConsolePathsLoadWorksWithArray()
    {
        $this->createFullConfiguration();

        $expected = array('console', '../bin/console', 'myconsole');

        $this->assertParameter($expected, 'pyrech_cron.console_paths');
    }

    public function testConsolePathsLoadWorksWithScalar()
    {
        $this->configuration = new ContainerBuilder();
        $config = $this->getFullConfig();
        $config['console_paths'] = 'mycustomconsole';

        $loader = new PyrechCronExtension();
        $loader->load(array($config), $this->configuration);

        $expected = array('console', '../bin/console', 'mycustomconsole');

        $this->assertParameter($expected, 'pyrech_cron.console_paths');
    }

    public function testTaskLoadWorksWithValidConfiguration()
    {
        $this->createFullConfiguration();

        $expected = array(
            'my_first_task' => array(
                'job' => 'echo 1',
                'frequency' => 'hourly'
            ),
            'my_second_task' => array(
                'job' => '@phpbin -v',
                'when' => array(
                    'minute' => 0,
                    'hour' => 2,
                    'day_of_the_week' => 6
                )
            )
        );

        $this->assertParameter($expected, 'pyrech_cron.tasks');
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfNoTaskIsGiven()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array();
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfTaskIsEmpty()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array(
            'my_task' => array()
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfJobIsEmpty()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array(
            'my_task' => array(
                'job' => '',
                'frequency' => 'daily'
            )
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfOnlyJobIsGiven()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array(
            'my_task' => array(
                'job' => 'echo 1'
            )
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfFrequencyIsInvalid()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array(
            'my_task' => array(
                'job' => 'echo 1',
                'frequency' => 'nonExistentFrequency'
            )
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfWhenIsEmpty()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array(
            'my_task' => array(
                'job' => 'echo 1',
                'when' => array()
            )
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTasksLoadThrowsExceptionIfWhenPartAndFrequencyPartAreGiven()
    {
        $loader = new PyrechCronExtension();
        $config = $this->getEmptyConfig();
        $config['tasks'] = array(
            'my_task' => array(
                'job' => 'echo 1',
                'frequency' => 'weekly',
                'when' => array(
                    'day_of_the_week' => 1
                )
            )
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    protected function createEmptyConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $config = $this->getEmptyConfig();

        $loader = new PyrechCronExtension();
        $loader->load(array($config), $this->configuration);

        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $config = $this->getFullConfig();

        $loader = new PyrechCronExtension();
        $loader->load(array($config), $this->configuration);

        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    protected function getFullConfig()
    {
        $yaml = <<<EOF
console_paths:
    - myconsole
tasks:
    my_first_task:
        job: "echo 1"
        frequency: "hourly"

    my_second_task:
        job: "@phpbin -v"
        when:
            minute: 0
            hour: 2
            day_of_the_week: 6
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * @param string $expected
     * @param string $key
     */
    private function assertAlias($expected, $key)
    {
        $this->assertEquals($expected, (string) $this->configuration->getAlias($key), sprintf('%s alias is correct', $key));
    }

    /**
     * @param mixed $expected
     * @param string $key
     */
    private function assertParameter($expected, $key)
    {
        $this->assertEquals($expected, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    /**
     * @param string $id
     */
    private function assertNotHasDefinition($id)
    {
        $this->assertFalse(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }

}
