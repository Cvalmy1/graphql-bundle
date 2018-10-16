<?php

namespace Despark\GraphQLBundle\DependencyInjection;

use Despark\GraphQLBundle\Factory\SchemaFactory;
use Despark\GraphQLBundle\GraphQLServiceManager;
use Despark\GraphQLBundle\GraphQLService;
use Despark\GraphQLBundle\ResolverRegistry;
use Despark\GraphQLBundle\Route\GraphQLRouteLoader;
use Digia\GraphQL\Schema\Schema;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class GraphQLExtension extends Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\Definition
     */
    private $routeLoaderDefinition;
    /**
     * @var \Symfony\Component\DependencyInjection\Definition
     */
    private $serviceManagerDefinition;

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');

        $this->routeLoaderDefinition = $container->getDefinition(GraphQLRouteLoader::class);
        $this->serviceManagerDefinition = $container->getDefinition(GraphQLServiceManager::class);

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['schemas'] as $name => $options) {
            $serviceDefinition = $this->addSchema($name, $options, $container);
            $this->registerService($name, $serviceDefinition);
        }

    }

    /**
     * @param string $name
     * @param array $options
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    private function addSchema(string $name, array $options, ContainerBuilder $container): Definition
    {
        $routeName = 'graphql.'.$name;
        $schemaName = 'graphql.schema.'.$name;
        $serviceName = 'graphql.service.'.$name;

        $this->routeLoaderDefinition->addMethodCall('addRoute', [$routeName, $options['route']]);

        // Register resolvers
        $resolverRegistryDefinition = $this->registerResolvers($name, $options['resolvers'], $container);

        // Create schema
        $schemaDefinition = $container->register($schemaName, Schema::class)
                                      ->setFactory(SchemaFactory::class.'::create')
                                      ->setArguments([$options['sdl'], $resolverRegistryDefinition]);

        return $container->register($serviceName, GraphQLService::class)
                         ->setArgument(0, $schemaDefinition);
    }

    /**
     * @param $name
     * @param \Symfony\Component\DependencyInjection\Definition $service
     */
    private function registerService(string $name, Definition $service)
    {
        $this->serviceManagerDefinition->addMethodCall('addService', [$name, $service]);
    }

    /**
     * @param string $name
     * @param array $resolvers
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    private function registerResolvers(string $name, array $resolvers, ContainerBuilder $container): Definition
    {
        $definition = $container->register('graphql.resolvers.'.$name, ResolverRegistry::class);

        foreach ($resolvers as $type => $resolverClass) {
            $resolverDefinition = $container->register($resolverClass, $resolverClass)
                                            ->setAutoconfigured(true)
                                            ->setAutowired(true);
            $definition->addMethodCall('addResolver', [$type, $resolverDefinition]);
        }

        return $definition;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'graphql';
    }
}