<?php

namespace Luckyseven\Bundle\LuckysevenJwtAuthBundle\DependencyInjection;

use Luckyseven\Bundle\JwtAuthBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LuckysevenJwtAuthExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
    }
}
