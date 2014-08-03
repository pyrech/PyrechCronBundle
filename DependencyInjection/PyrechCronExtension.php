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
