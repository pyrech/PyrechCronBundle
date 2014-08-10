<?php

namespace Pyrech\CronBundle\DependencyInjection;

use Pyrech\CronBundle\Util\Operator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pyrech_cron');

        $this->addConsolePathSection($rootNode);
        $this->addTaskSection($rootNode);

        return $treeBuilder;
    }

    private function addConsolePathSection(ArrayNodeDefinition $node)
    {
        $node
            ->fixXmlConfig('console_path', 'console_paths')
            ->children()
                ->arrayNode('console_paths')
                    ->info('Console path configuration')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function($path) { return array($path); })
                    ->end()
                    ->prototype('scalar')
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addTaskSection(ArrayNodeDefinition $node)
    {
        $frequencies = array('hourly', 'daily', 'weekly', 'monthly', 'yearly');

        $node
            ->fixXmlConfig('task', 'tasks')
            ->children()
                ->arrayNode('tasks')
                    ->info('Tasks configuration')
                    ->canBeUnset()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->validate()
                            ->ifTrue(function($value) {
                                return (isset($value['frequency']) && isset($value['when']))
                                    || !(isset($value['frequency']) || isset($value['when']));
                            })
                            ->thenInvalid('Task should have a `frequency` or `when` configuration')
                        ->end()
                        ->children()
                            ->scalarNode('job')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->info('If present, `@phpbin` will be replaced by the real path of the php bin')
                                ->example('@binphp /var/www/myapp/myscript.php')
                            ->end()
                            ->scalarNode('frequency')
                                ->validate()
                                    ->ifNotInArray($frequencies)
                                    ->thenInvalid('Invalid frequency configuration "%s"')
                                ->end()
                                ->example($frequencies)
                            ->end()
                            ->arrayNode('when')
                                ->validate()
                                    ->ifTrue(function($value) {
                                        return empty($value);
                                    })
                                    ->thenInvalid('Invalid when configuration "%s"')
                                ->end()
                                ->children()
                                    ->scalarNode('minute')->end()
                                    ->scalarNode('hour')->end()
                                    ->scalarNode('day')->end()
                                    ->scalarNode('month')->end()
                                    ->scalarNode('day_of_the_week')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
