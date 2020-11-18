<?php
declare(strict_types = 1);

namespace Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\DependencyInjection;

use Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\SalesforceApi;
use Maba\GentleForce\RateLimit\UsageRateLimit;
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
        
        $definition = $container->getDefinition(
            Configuration::CONFIG_ROOT . '.dependency.developerforce.force_com_toolkit_for_php.sforce_enterprise_client'
        );
        $definition->addMethodCall(
            'createConnection',
            [
                '$wsdl' => __DIR__ . '../../salesforce.wsdl.xml',
            ]
        );
        $definition->addMethodCall(
            'login',
            [
                '$username' => $mergedConfig['username'],
                '$password' => $mergedConfig['password'] . $mergedConfig['token'],
            ]
        );
        
        $definition = $container->getDefinition(
            Configuration::CONFIG_ROOT . '.dependency.jalismrs.symfony_bundle_api_throttler.api_throttler'
        );
        $definition->addMethodCall(
            'registerRateLimits',
            [
                '$useCaseKey' => SalesforceApi::THROTTLER_KEY,
                '$rateLimits' => [
                    new UsageRateLimit(
                        100000,
                        60 * 60 * 24
                    ),
                ],
            ]
        );
    }
}
