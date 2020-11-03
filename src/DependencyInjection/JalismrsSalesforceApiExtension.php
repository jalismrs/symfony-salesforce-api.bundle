<?php
declare(strict_types = 1);

namespace Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class JalismrsSalesforceApiExtension
 *
 * @package Jalismrs\Symfony\Bundle\JalismrsApiThrottlerBundle\DependencyInjection
 */
class JalismrsSalesforceApiExtension extends
    ConfigurableExtension
{
    /**
     * loadInternal
     *
     * @param array $mergedConfig
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function loadInternal(
        array $mergedConfig,
        ContainerBuilder $container
    ) : void {
        $fileLocator = new FileLocator(
            __DIR__ . '/../Resources/config'
        );
        
        $yamlFileLoader = new YamlFileLoader(
            $container,
            $fileLocator
        );
        
        $yamlFileLoader->load('services.yaml');
    
        $definition = $container->getDefinition(Configuration::CONFIG_ROOT . '.salesforce_api');
        
        $definition->replaceArgument(
            '$username',
            $mergedConfig['username']
        );
        $definition->replaceArgument(
            '$password',
            $mergedConfig['password']
        );
        $definition->replaceArgument(
            '$token',
            $mergedConfig['token']
        );
    }
}
