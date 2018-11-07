<?php

namespace Despark\GraphQLBundle;

use Digia\GraphQL\Schema\Resolver\ResolverInterface;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ResolverRegistry
 * @package Despark\GraphQLBundle
 */
class ResolverRegistry implements Arrayable
{

    /**
     * @var \Digia\GraphQL\Schema\Resolver\ResolverInterface[]
     */
    private $resolvers = [];

    public function addResolver(string $name, ResolverInterface $resolver, string $parent = null)
    {
        $this->resolvers[$name] = [
            'resolver' => $resolver,
            'parent' => $parent,
        ];
    }

    /**
     * @return \Digia\GraphQL\Schema\Resolver\ResolverInterface[]
     */
    public function getResolvers(): array
    {
        return $this->resolvers;
    }

    public function toArray()
    {
        $a = [];
        foreach ($this->resolvers as $type => $resolverOptions) {
            if (is_null($resolverOptions['parent'])) {
                $a[$type] = $resolverOptions['resolver'];
            }
        }
    }

}