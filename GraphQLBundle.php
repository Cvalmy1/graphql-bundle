<?php


namespace Despark\GraphQLBundle;


use Despark\GraphQLBundle\DependencyInjection\GraphQLExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GraphQLBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {

    }

    /**
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        return GraphQLExtension::class;
    }

}