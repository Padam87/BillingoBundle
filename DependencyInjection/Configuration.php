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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('padam87_billingo');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('authentication')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->children()
                        ->scalarNode('public_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('private_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->integerNode('lifetime')
                            ->cannotBeEmpty()
                            ->defaultValue(120)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('version')
                            ->cannotBeEmpty()
                            ->defaultValue(2)
                        ->end()
                        ->scalarNode('base_url')
                            ->cannotBeEmpty()
                            ->defaultValue('https://www.billingo.hu/api/')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
