<?php

namespace Padam87\BillingoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('padam87_billingo');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('authentication')
                    ->isRequired()
                    ->children()
                        ->scalarNode('token')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->integerNode('lifetime')
                            ->defaultValue(180)
                        ->end()
                        ->integerNode('time_offset')
                            ->defaultValue(-90)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_url')
                            ->cannotBeEmpty()
                            ->defaultValue('https://api.billingo.hu/v3/')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
