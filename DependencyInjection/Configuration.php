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
                            ->variableNode('resolvers')
                                ->isRequired()
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
     *
     * @param bool $recursed
     *
     * @return void
     * @throws \Despark\GraphQLBundle\Exceptions\ConfigurationException
     */
    private function validateResolvers(array $resolvers, $recursed = false): void
    {
        if (!$recursed && !array_key_exists('Query', $resolvers)) {
            throw new ConfigurationException('Missing query resolver');
        }

        foreach ($resolvers as $resolver) {
            if (is_array($resolver)) {
                $this->validateResolvers($resolver, true);
            } elseif (!class_exists($resolver)) {
                throw new ConfigurationException(sprintf('Resolver class: `%s` does not exist', $resolver));
            } else {
                if (!is_a($resolver, ResolverInterface::class, true)) {
                    throw new ConfigurationException(
                        sprintf(
                            'Resolver class: `%s` does not implement `%s` interface',
                            $resolver,
                            ResolverInterface::class
                        )
                    );
                }
            }
        }
    }

    /**
     * @param array $resolvers
     *
     * @return array
     * @deprecated
     */
    private function normalizeResolvers(array $resolvers): array
    {
        $normalizedResolvers = [];

        foreach ($resolvers as $key => &$value) {
            if (is_array($value)) {
                $value = $this->normalizeResolvers($value);
            }
            $normalizedResolvers[ucfirst($key)] = $value;
        }

        return $normalizedResolvers;
    }
}