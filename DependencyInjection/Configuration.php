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

        $this->addTaskSection($rootNode);

        return $treeBuilder;
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
                            ->thenUnset('Task should have a `frequency` or `when` configuration')
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
                                    ->integerNode('minute')
                                        ->min(0)->max(59)
                                    ->end()
                                    ->integerNode('hour')
                                        ->min(0)->max(23)
                                    ->end()
                                    ->integerNode('day')
                                        ->min(1)->max(31)
                                    ->end()
                                    ->integerNode('month')
                                        ->min(1)->max(12)
                                    ->end()
                                    ->integerNode('day_of_the_week')
                                        ->min(0)->max(6)
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
