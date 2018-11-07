<?php

namespace Despark\GraphQLBundle\Factory;

use Despark\GraphQLBundle\ResolverRegistry;
use Digia\GraphQL\Language\FileSourceBuilder;
use function Digia\GraphQL\buildSchema;

/**
 * Class SchemaFactory
 * @package Despark\GraphQLBundle\Factory
 */
class SchemaFactory
{
    /**
     * @param string $sdlPath
     * @param array $resolverRegistry
     * @return \Digia\GraphQL\Schema\Schema
     * @throws \Digia\GraphQL\Error\FileNotFoundException
     * @throws \Digia\GraphQL\Error\InvariantException
     */
    public static function create(string $sdlPath, ResolverRegistry $resolverRegistry)
    {
        $sourceBuilder = new FileSourceBuilder($sdlPath);

        return buildSchema($sourceBuilder->build(), $resolverRegistry->toArray());
    }

}