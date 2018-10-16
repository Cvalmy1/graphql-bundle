<?php

namespace Despark\GraphQLBundle\Tests\DependencyInjection;

use Despark\GraphQLBundle\DependencyInjection\GraphQLExtension;
use Despark\GraphQLBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GraphQLBundleExtensionTest
 * @package Despark\GraphQLBundle\Tests\DependencyInjection
 */
class GraphQLBundleExtensionTest extends TestCase
{
    /**
     * @var \Despark\GraphQLBundle\DependencyInjection\GraphQLExtension
     */
    private $graphQLExtension;

    protected function setUp()
    {
        $this->graphQLExtension = new GraphQLExtension();
    }

    public function testConfigurationWithoutSchema()
    {
        $config = [];
        $container = new ContainerBuilder();

        $this->graphQLExtension->load($config, $container);
    }

}