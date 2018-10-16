<?php


namespace Despark\GraphQLBundle\DependencyInjection;


use Despark\GraphQLBundle\Exceptions\ConfigurationException;
use Digia\GraphQL\Schema\Resolver\ResolverInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    const DEFAULT_ROUTE = '/graphql';

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
                ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('sdl')
                                ->info('Path to the SDL graphql definition')
                            ->end()
                            ->scalarNode('route')
                                ->defaultValue(self::DEFAULT_ROUTE)
                                ->treatNullLike(self::DEFAULT_ROUTE)
                            ->end()
                            ->arrayNode('resolvers')
                                ->useAttributeAsKey('type')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->beforeNormalization()
                                    ->always(function($resolvers){
                                        return $this->normalizeResolvers($resolvers);
                                    })
                                ->end()
                                ->prototype('scalar')->end()
                                ->validate()
                                ->always(function($resolvers){
                                    $this->validateResolvers($resolvers);
                                    return $resolvers;
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        //@formatter:on

        return $treeBuilder;
    }

    /**
     * @param array $resolvers
     * @return void
     * @throws \Despark\GraphQLBundle\Exceptions\ConfigurationException
     */
    private function validateResolvers(array $resolvers): void
    {
        if (!array_key_exists('Query', $resolvers)) {
            throw new ConfigurationException('Missing query resolver');
        }

        foreach ($resolvers as $class) {
            if (!class_exists($class)) {
                throw new ConfigurationException(sprintf('Resolver class: `%s` does not exist', $class));
            } else {
                if (!is_a($class, ResolverInterface::class, true)) {
                    throw new ConfigurationException(
                        sprintf(
                            'Resolver class: `%s` does not implement `%s` interface',
                            $class,
                            ResolverInterface::class
                        )
                    );
                }
            }
        }
    }

    private function normalizeResolvers(array $resolvers): array
    {
        return array_combine(
            array_map('ucfirst', array_keys($resolvers)),
            array_values($resolvers)
        );
    }
}