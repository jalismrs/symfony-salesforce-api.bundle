<?php
declare(strict_types = 1);

namespace Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Jalismrs\Symfony\Bundle\JalismrsApiThrottlerBundle\DependencyInjection
 */
class Configuration implements
    ConfigurationInterface
{
    public const CONFIG_ROOT = 'jalismrs_salesforce_api';
    
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::CONFIG_ROOT);
        
        // @formatter:off
        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('username')
                    ->info('Salesforce username')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->info('Salesforce password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('token')
                    ->info('Salesforce API token')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
        // @formatter:on
        
        return $treeBuilder;
    }
}
