<?php
declare(strict_types = 1);

namespace Tests;

use Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\SalesforceApi;

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
