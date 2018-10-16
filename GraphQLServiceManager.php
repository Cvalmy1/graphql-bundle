<?php

namespace Despark\GraphQLBundle;

/**
 * Class GraphQLServiceManager
 * @package Despark\GraphQLBundle
 */
class GraphQLServiceManager implements GraphQLServiceManagerInterface
{
    /**
     * @var \Despark\GraphQLBundle\GraphQLServiceInterface[]
     */
    private $services = [];

    /**
     * @param string $name
     * @param \Despark\GraphQLBundle\GraphQLServiceInterface $service
     */
    public function addService(string $name, GraphQLServiceInterface $service)
    {
        $this->services[$name] = $service;
    }

    /**
     * @param string $name
     * @return \Despark\GraphQLBundle\GraphQLServiceInterface|null
     */
    public function getService(string $name): ?GraphQLServiceInterface
    {
        return $this->services[$name] ?? null;
    }

}