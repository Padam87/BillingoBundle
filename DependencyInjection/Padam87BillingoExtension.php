<?php

namespace Padam87\BillingoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class Padam87BillingoExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $authenticator = $container->getDefinition('padam87_billingo.authenticator');
        $authenticator->addMethodCall('setConfig', [$config['authentication']]);

        $api = $container->getDefinition('padam87_billingo.api');
        $api->addMethodCall('setConfig', [$config['api']]);
    }
}
