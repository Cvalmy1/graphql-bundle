<?php


namespace Despark\GraphQLBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('graphql');

        //@formatter:off
        $rootNode
            ->children()
                ->arrayNode('schemas')
                    ->arrayPrototype()
                        ->useAttributeAsKey('name')
                        ->children()
                            ->scalarNode('path')->end()
                            ->scalarNode('query_resolver')->end()
                            ->scalarNode('mutation_resolver')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        //@formatter:on

        return $treeBuilder;
    }
}