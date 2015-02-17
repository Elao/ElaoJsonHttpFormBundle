<?php

namespace Elao\Bundle\JsonHttpFormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Json Http Bundle Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('elao_json_http_form');

        return $treeBuilder;
    }
}
