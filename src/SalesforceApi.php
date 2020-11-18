<?php
declare(strict_types = 1);

namespace Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle;

use Jalismrs\Symfony\Bundle\JalismrsApiThrottlerBundle\ApiThrottler;
use Maba\GentleForce\RateLimit\UsageRateLimit;
use QueryResult;
use SforceEnterpriseClient;
use SObject;

/**
 * Class SalesforceApi
 *
 * @package Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle
 */
class SalesforceApi
{
    public const THROTTLER_KEY = 'salesforce_api';
    
    /**
     * apiThrottler
     *
     * @var \Jalismrs\Symfony\Bundle\JalismrsApiThrottlerBundle\ApiThrottler
     */
    private ApiThrottler $apiThrottler;
    /**
     * client
     *
     * @var \SforceEnterpriseClient
     */
    private SforceEnterpriseClient $client;
    
    /**
     * SalesforceApi constructor.
     *
     * @param \Jalismrs\Symfony\Bundle\JalismrsApiThrottlerBundle\ApiThrottler $apiThrottler
     * @param \SforceEnterpriseClient                                          $sforceEnterpriseClient
     */
    public function __construct(
        ApiThrottler $apiThrottler,
        SforceEnterpriseClient $sforceEnterpriseClient
    ) {
        $this->apiThrottler = $apiThrottler;
        $this->client       = $sforceEnterpriseClient;
    }
    
    /**
     * queryOneOrFails
     *
     * @param string $query
     *
     * @return \SObject
     *
     * @throws \Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\SalesforceApiException
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function queryOneOrFails(
        string $query
    ) : SObject {
        $result = $this->queryOne($query);
        if ($result === null) {
            throw new SalesforceApiException(
                'No result'
            );
        }
        
        return $result;
    }
    
    /**
     * queryOne
     *
     * @param string $query
     *
     * @return \SObject|null
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function queryOne(
        string $query
    ) : ?SObject {
        $queryResult = $this->query($query);
        
        return $queryResult->size === 0
            ? null
            : $queryResult->current();
    }
    
    /**
     * query
     *
     * @param string $query
     *
     * @return \QueryResult
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function query(
        string $query
    ) : QueryResult {
        $this->apiThrottler->waitAndIncrease(
            self::THROTTLER_KEY,
            ''
        );
        
        return $this->client->query($query);
    }
}
