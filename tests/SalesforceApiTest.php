<?php
declare(strict_types = 1);

namespace Tests;

use Jalismrs\ApiThrottlerBundle\ApiThrottler;
use Jalismrs\SalesforceApiBundle\SalesforceApi;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use QueryResult;
use SforceEnterpriseClient;
use SObject;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use function count;

/**
 * Class SalesforceApiTest
 *
 * @package Tests
 *
 * @covers  \Jalismrs\SalesforceApiBundle\SalesforceApi
 */
final class SalesforceApiTest extends
    TestCase
{
    /**
     * mockApiThrottler
     *
     * @var \PHPUnit\Framework\MockObject\MockObject|\Jalismrs\ApiThrottlerBundle\ApiThrottler
     */
    private MockObject $mockApiThrottler;
    /**
     * mockSforceEnterpriseClient
     *
     * @var \PHPUnit\Framework\MockObject\MockObject|\SforceEnterpriseClient
     */
    private MockObject $mockSforceEnterpriseClient;
    
    /**
     * testQuery
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testQuery() : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        $query           = 'test';
        $mockQueryResult = $this->createMock(QueryResult::class);
        
        // expect
        $this->mockSforceEnterpriseClient
            ->expects(self::once())
            ->method('query')
            ->with(
                self::equalTo($query)
            )
            ->willReturn($mockQueryResult);
        $this->mockApiThrottler
            ->expects(self::atLeastOnce())
            ->method('waitAndIncrease');
        
        // act
        $output = $systemUnderTest->query($query);
        
        // assert
        self::assertSame(
            $mockQueryResult,
            $output
        );
    }
    
    /**
     * testQueryOne
     *
     * @param bool $providedOutput
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     *
     * @dataProvider \Tests\SalesforceApiProvider::provideQueryOne
     */
    public function testQueryOne(
        bool $providedOutput
    ) : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        $query           = 'test';
        $mockQueryResult = $this->createMock(QueryResult::class);
        
        if ($providedOutput) {
            $mockQueryResult->size = 1;
    
            $expectedOutput = new SObject(
                (object)[
                    'Id' => 'id',
                ],
            );
    
            // expect
            $mockQueryResult
                ->expects(self::once())
                ->method('current')
                ->willReturn($expectedOutput);
        } else {
            $mockQueryResult->size = 0;
    
            $expectedOutput = null;
    
            // expect
            $mockQueryResult
                ->expects(self::never())
                ->method('current');
        }
        
        // expect
        $this->mockSforceEnterpriseClient
            ->expects(self::once())
            ->method('query')
            ->with(
                self::equalTo($query)
            )
            ->willReturn($mockQueryResult);
        $this->mockApiThrottler
            ->expects(self::atLeastOnce())
            ->method('waitAndIncrease');
        
        // act
        $output = $systemUnderTest->queryOne($query);
        
        // assert
        self::assertEquals(
            $expectedOutput,
            $output
        );
    }
    
    /**
     * createSUT
     *
     * @return \Jalismrs\SalesforceApiBundle\SalesforceApi
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     */
    private function createSUT() : SalesforceApi
    {
        $this->mockSforceEnterpriseClient
            ->expects(self::once())
            ->method('createConnection');
        $this->mockSforceEnterpriseClient
            ->expects(self::once())
            ->method('login')
            ->with(
                self::equalTo(SalesforceApiProvider::PARAMETER_USERNAME),
                self::equalTo(SalesforceApiProvider::PARAMETER_PASSWORD . SalesforceApiProvider::PARAMETER_TOKEN)
            );
        $this->mockApiThrottler
            ->expects(self::once())
            ->method('registerRateLimits');
        
        $testParameterBag = new ParameterBag(SalesforceApiProvider::PARAMETERS);
        
        return new SalesforceApi(
            $this->mockApiThrottler,
            $testParameterBag,
            $this->mockSforceEnterpriseClient
        );
    }
    
    /**
     * setUp
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();
        
        $this->mockApiThrottler           = $this->createMock(ApiThrottler::class);
        $this->mockSforceEnterpriseClient = $this->createMock(SforceEnterpriseClient::class);
    }
}
