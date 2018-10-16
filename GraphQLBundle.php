<?php


namespace Despark\GraphQLBundle;


use Despark\GraphQLBundle\DependencyInjection\GraphQLExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GraphQLBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {

    }

    /**
     * @return bool|null|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $extension = $this->createContainerExtension();

            if (null !== $extension) {
                if (!$extension instanceof ExtensionInterface) {
                    throw new \LogicException(sprintf('Extension %s must implement Symfony\Component\DependencyInjection\Extension\ExtensionInterface.',
                        \get_class($extension)));
                }
                $this->extension = $extension;
            } else {
                $this->extension = false;
            }
        }

        if ($this->extension) {
            return $this->extension;
        }
    }

    /**
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        return GraphQLExtension::class;
    }
}