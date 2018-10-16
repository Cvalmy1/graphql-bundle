<?php

namespace Despark\GraphQLBundle;


/**
 * Class GraphQLServiceManager
 * @package Despark\GraphQLBundle
 */
interface GraphQLServiceManagerInterface
{
    /**
     * @param string $name
     * @param \Despark\GraphQLBundle\GraphQLServiceInterface $service
     * @return
     */
    public function addService(string $name, GraphQLServiceInterface $service);

    /**
     * @param string $name
     * @return \Despark\GraphQLBundle\GraphQLServiceInterface|null
     */
    public function getService(string $name): ?GraphQLServiceInterface;
}