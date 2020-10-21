<?php
declare(strict_types = 1);

namespace Tests;

use Jalismrs\SalesforceApiBundle\SalesforceApi;

/**
 * Class SalesforceApiProvider
 *
 * @package Tests
 */
final class SalesforceApiProvider
{
    public const PARAMETER_USERNAME = 'apiSalesforceUsername';
    public const PARAMETER_PASSWORD = 'apiSalesforcePassword';
    public const PARAMETER_TOKEN    = 'apiSalesforceToken';
    public const PARAMETERS         = [
        SalesforceApi::PARAMETER_USERNAME => self::PARAMETER_USERNAME,
        SalesforceApi::PARAMETER_PASSWORD => self::PARAMETER_PASSWORD,
        SalesforceApi::PARAMETER_TOKEN    => self::PARAMETER_TOKEN,
    ];
    
    /**
     * provideQueryOne
     *
     * @return array
     */
    public function provideQueryOne() : array
    {
        return [
            'without result' => [
                'output' => false,
            ],
            'with result'    => [
                'output' => true,
            ],
        ];
    }
}
