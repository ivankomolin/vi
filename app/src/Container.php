<?php

namespace App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;

class Container
{
    /**
     * @return TaggedContainerInterface
     */
    public static function create(): TaggedContainerInterface
    {
        $rootDir = dirname(__DIR__);
        $configDir = $rootDir . '/config';
        $container = new ContainerBuilder();
        $container->setParameter('root_dir', $rootDir);
        $container->setParameter('config_dir', $configDir);
        (new YamlFileLoader($container, new FileLocator($configDir)))
            ->load('services.yml');

        $container->compile(true);

        return $container;
    }
}
