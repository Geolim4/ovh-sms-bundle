<?php

namespace A2zi\OvhSmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ovh_sms');
        $rootNode

            ->children()
            ->info('ce que my_type configure')
            ->example('exemple de paramètre')

            ->scalarNode('application_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('application_secret')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('endpoint')->defaultValue('ovh-eu')->end()
            ->scalarNode('sms_service_id')->defaultNull()->end()
            ->scalarNode('sender')->defaultNull()->end();

        return $treeBuilder;
    }
}
