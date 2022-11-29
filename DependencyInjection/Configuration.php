<?php

namespace Luckyseven\Bundle\JwtAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * LuckysevenJwtAuth Configuration.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('luckyseven_jwt_auth');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('foo')
                ->defaultValue('default_bar')
                ->end()
            ->end();
        return $treeBuilder;
    }
}
