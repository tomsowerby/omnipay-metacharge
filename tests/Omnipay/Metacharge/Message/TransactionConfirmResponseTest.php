<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Tests\TestCase;

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
}
