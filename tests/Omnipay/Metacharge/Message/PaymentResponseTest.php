<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new PaymentResponse($this->getMockRequest(), 'example=value&foo=bar');
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testRedirectMethodIsPost()
    {
        $httpResponse = $this->getMockHttpResponse('PaymentSuccess.txt');
        $response = new PaymentResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertEquals('POST', $response->getRedirectMethod());
    }

    public function testSuccessWithout3dsOrAvsOrCv2()
    {
        $httpResponse = $this->getMockHttpResponse('PaymentSuccess.txt');
        $response = new PaymentResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345678', $response->getTransactionId());
        $this->assertNotNull($response->getSecurityToken());
        $this->assertNotNull($response->getTime());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getScheduleId());
        $this->assertNull($response->get3DSResult());
        $this->assertNull($response->getAVS());
        $this->assertNull($response->getCV2());
    }

    public function testSuccessWithScheduled()
    {
        $httpResponse = $this->getMockHttpResponse('PaymentWithScheduleSuccess.txt');
        $response = new PaymentResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getScheduleId());
    }

    public function testSuccessWithOriginalAmounts()
    {
        $httpResponse = $this->getMockHttpResponse('PaymentWithOriginalAmountSuccess.txt');
        $response = new PaymentResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getOriginalAmount());
        $this->assertNotNull($response->getOriginalCurrency());
    }

    //@todo Test AVS, 3DSecure, CV2 response returns

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PaymentFailure.txt');
        $response = new PaymentResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getScheduleId());
        $this->assertNull($response->get3DSResult());
        $this->assertNull($response->getAVS());
        $this->assertNull($response->getCV2());
        $this->assertNotNull($response->getTime());
    }
}
