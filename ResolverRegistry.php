<?php

namespace Despark\GraphQLBundle;

use Digia\GraphQL\Schema\Resolver\ResolverInterface;

/**
 * Class ResolverRegistry
 * @package Despark\GraphQLBundle
 */
class ResolverRegistry
{

    /**
     * @var \Digia\GraphQL\Schema\Resolver\ResolverInterface[]
     */
    private $resolvers = [];

    public function addResolver($name, ResolverInterface $resolver)
    {
        $this->resolvers[$name] = $resolver;
    }

    /**
     * @return \Digia\GraphQL\Schema\Resolver\ResolverInterface[]
     */
    public function getResolvers(): array
    {
        return $this->resolvers;
    }

}