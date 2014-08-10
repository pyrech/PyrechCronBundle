<?php

namespace Pyrech\CronBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PyrechCronExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->processConsolePaths($config, $container);
        $this->processTasks($config, $container);
    }

    private function processConsolePaths($config, ContainerBuilder $container)
    {
        $paths = $container->getParameter('pyrech_cron.console_paths');
        if (isset($config['console_paths']) && is_array($config['console_paths'])) {
            $paths = array_merge($paths, $config['console_paths']);
        }
        $container->setParameter('pyrech_cron.console_paths', $paths);
    }

    private function processTasks($config, ContainerBuilder $container)
    {
        $tasks = array();
        if (isset($config['tasks']) && is_array($config['tasks'])) {
            $tasks = $config['tasks'];
        }
        $container->setParameter('pyrech_cron.tasks', $tasks);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'pyrech_cron';
    }
}
