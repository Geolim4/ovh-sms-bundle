<?php

namespace A2zi\OvhSmsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class A2ziOvhSmsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $container->setParameter('a2zi_ovh_sms.sender',$config['sender']);
        $container->setParameter('a2zi_ovh_sms.application_key',$config['application_key']);
        $container->setParameter('a2zi_ovh_sms.application_secret',$config['application_secret']);
        $container->setParameter('a2zi_ovh_sms.consumer_key',$config['consumer_key']);
        $container->setParameter('a2zi_ovh_sms.sms_service_id',$config['sms_service_id']);
        $container->setParameter('a2zi_ovh_sms.endpoint',$config['endpoint']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

    }


}
