<?php

namespace Despark\GraphQLBundle\Route;

use Despark\GraphQLBundle\Controller\GraphQLController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class GraphQLRouteLoader
 * @package Despark\GraphQLBundle\Route
 */
class GraphQLRouteLoader
{

    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    private $routeCollection;


    /**
     * GraphQLRouteLoader constructor.
     */
    public function __construct()
    {
        $this->routeCollection = new RouteCollection();
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function loadRoutes()
    {
        return $this->routeCollection;

    }

    /**
     * @param string $name
     * @param string $path
     */
    public function addRoute(string $name, string $path)
    {
        $defaults = [
            '_controller' => GraphQLController::class.'::handle',
        ];

        $this->routeCollection->add($name, new Route($path, $defaults));
    }

}