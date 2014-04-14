<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Tests\TestCase;

/**
 * Class TransactionConfirmResponseTest.
 *
 * Created on: 09/04/2014
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class TransactionConfirmResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new TransactionConfirmResponse($this->getMockRequest(), 'example=value&foo=bar');
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('TransactionConfirmFailure.txt');
        $response = new TransactionConfirmResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getResponse());
        $this->assertNotNull($response->getTime());
    }

    public function testResponseArray()
    {
        $httpResponse = $this->getMockHttpResponse('TransactionConfirmSuccess.txt');
        $response = new TransactionConfirmResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(is_array($response->getResponseArray()));
        $originalResponse = $response->getResponse();
        $this->assertEquals(10.00, $originalResponse['fltAmount']);
        $this->assertNotNull($response->getTime());
    }

    /**
     * testResponse.
     *
     * @todo Test object returns here once we're returning Response objects from getResponse.
     */
    public function testResponse()
    {
        $httpResponse = $this->getMockHttpResponse('TransactionConfirmSuccess.txt');
        $response = new TransactionConfirmResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getResponse());
        $this->assertNotNull($response->getTime());
    }
}
