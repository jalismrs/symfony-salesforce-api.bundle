<?php
declare(strict_types = 1);

namespace Jalismrs\SalesforceApiBundle;

use Jalismrs\ApiThrottlerBundle\ApiThrottler;
use Maba\GentleForce\RateLimit\UsageRateLimit;
use QueryResult;
use SforceEnterpriseClient;
use SObject;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function vsprintf;

/**
 * Class SalesforceClient
 *
 * @package App\Service\Api
 */
class SalesforceApi
{
    public const PARAMETER_USERNAME = 'salesforce_api.username';
    public const PARAMETER_PASSWORD = 'salesforce_api.password';
    public const PARAMETER_TOKEN    = 'salesforce_api.token';
    
    private const THROTTLER_KEY = 'salesforce_api';
    
    /**
     * apiThrottler
     *
     * @var \Jalismrs\ApiThrottlerBundle\ApiThrottler
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
     * @param \Jalismrs\ApiThrottlerBundle\ApiThrottler                                 $apiThrottler
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $parameterBag
     * @param \SforceEnterpriseClient                                                   $sforceEnterpriseClient
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     */
    public function __construct(
        ApiThrottler $apiThrottler,
        ParameterBagInterface $parameterBag,
        SforceEnterpriseClient $sforceEnterpriseClient
    ) {
        $this->apiThrottler = $apiThrottler;
        $this->client       = $sforceEnterpriseClient;
        
        $username = $parameterBag->get(self::PARAMETER_USERNAME);
        $password = vsprintf(
            '%s%s',
            [
                $parameterBag->get(self::PARAMETER_PASSWORD),
                $parameterBag->get(self::PARAMETER_TOKEN),
            ],
        );
        
        $this->client->createConnection(
            __DIR__ . '/../salesforce.wsdl.xml'
        );
        $this->client->login(
            $username,
            $password
        );
        
        $this->apiThrottler->registerRateLimits(
            self::THROTTLER_KEY,
            [
                new UsageRateLimit(
                    100000,
                    60 * 60 * 24
                ),
            ]
        );
    }
    
    /**
     * queryOneOrFails
     *
     * @param string $query
     *
     * @return \SObject
     *
     * @throws \Jalismrs\SalesforceApiBundle\SalesforceApiException
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
