<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Tests\TestCase;

class FraudGuardCheckResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new FraudGuardCheckResponse($this->getMockRequest(), 'example=value&foo=bar');
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testSuccessWithout3dsOrAvsOrCv2()
    {
        $httpResponse = $this->getMockHttpResponse('FraudGuardCheckSuccess.txt');
        $response = new FraudGuardCheckResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345678', $response->getTransactionId());
        $this->assertNotNull($response->getTime());
        $this->assertNull($response->getMessage());
        $this->assertNotNull($response->getAdvisory());
        $this->assertNotNull($response->getFraudScore());
        $this->assertEquals(0, $response->getCountryIP());
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FraudGuardCheckFailure.txt');
        $response = new FraudGuardCheckResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getAdvisory());
        $this->assertNotNull($response->getTime());
    }
}
