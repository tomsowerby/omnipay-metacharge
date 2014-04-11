<?php
/**
 * GatewayTest.php
 *
 * Created on: 01/04/14
 * 
 * @package 
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */

namespace Omnipay\Metacharge;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setInstId(1234);
        $gateway->set3DSecureResponseUrl('http://www.someurl.com');
        $this->gateway = $gateway;

        $this->paymentRequiredOptions = array(
            'cartId' => 4567,
            'description' => 'A description',
            'amount' => '10.00',
            'currency' => 'GBP',
            'card' => new CreditCard(array(
                    'firstName' => 'Joe',
                    'lastName' => 'Bloggs',
                    'postcode' => 'BA12BU',
                    'email' => 'test@paypoint.net',
                    'number' => '4111111111111111',
                    'expiryMonth' => '02',
                    'expiryYear' => '2016',
                    'cvv' => '123',
                )),
        );

        $this->refundRequiredOptions = array(
            'transactionId' => 4567,
            'securityToken' => 'MockToken',
            'amount' => '10.00',
        );

        $this->repeatRequiredOptions = $this->refundRequiredOptions;
        $this->preAuthCaptureRequiredOptions = $this->refundRequiredOptions;
        $this->preAuthVoidRequiredOptions = $this->refundRequiredOptions;

        $this->subscriptionCancellationRequiredOptions = array(
            'scheduleId' => 890,
        );

        $this->transactionConfirmRequiredOptions = array(
            'cartId' => 4567,
            'confirmType' => 'PAYMENT'
        );

        $this->payoutRequiredOptions = array(
            'cartId' => 4567,
            'description' => 'A description',
            'amount' => '10.00',
            'currency' => 'GBP',
            'card' => new CreditCard(array(
                    'firstName' => 'Joe',
                    'lastName' => 'Bloggs',
                    'email' => 'test@paypoint.net',
                    'number' => '4111111111111111',
                    'expiryMonth' => '02',
                    'expiryYear' => '2016',
                )),
        );

        $this->fraudGuardCheckRequiredOptions = array(
            'cartId' => 4567,
            'description' => 'A description',
            'card' => new CreditCard(array(
                    'firstName' => 'Joe',
                    'lastName' => 'Bloggs',
                    'city' => 'London',
                    'country' => 'UK',
                    'email' => 'test@paypoint.net',
                )),
        );

        $this->s3DAuthorisationResumeRequiredOptions = array(
            'transactionId' => 4567,
            'securityToken' => 'MockToken',
            's3DTransID' => '123456',
            's3DResponse' => 'response from 3D Secure',
            's3DMerchantData' => 'merchant data',
        );
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('PaymentSuccess.txt');

        $request = $this->gateway->purchase($this->paymentRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('12345678', $response->getTransactionId());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getFraudScore()); //Not included in mock response
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseWithRedirect()
    {
        //@todo This mock response has been created based on the documentation, check actual responses and update accordingly.
        $this->setMockHttpResponse('PaymentWith3DSecureRedirectSuccess.txt');

        $request = $this->gateway->purchase($this->paymentRequiredOptions);
        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('12345678', $response->getTransactionId());

        $this->assertNotNull($response->getS3DTransID());
        $this->assertNotNull($response->getS3DMerchantData());

        $this->assertEquals('http://www.redirect.com', $response->getRedirectUrl());

        $expected = array();
        $expected['PaReq'] = 'reqData';
        $expected['MD'] = $response->getS3DMerchantData();
        $expected['TermUrl'] = $this->gateway->get3DSecureResponseUrl();

        $this->assertEquals($expected, $response->getRedirectData());

        $this->assertNull($response->getMessage());
    }

    public function testRefund()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $request = $this->gateway->refund($this->refundRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testPayout()
    {
        //digest needed for this one
        $this->gateway->setSharedKey('anythingForNow');

        //@todo PayoutSuccess is just a copy of PaymentSuccess. Update it to be a as-live response.
        $this->setMockHttpResponse('PayoutSuccess.txt');

        $request = $this->gateway->payout($this->payoutRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testRepeatPayment()
    {
        $this->setMockHttpResponse('RepeatPaymentSuccess.txt');

        $request = $this->gateway->repeatPayment($this->repeatRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());

        //With CVV
        $this->setMockHttpResponse('RepeatPaymentSuccess.txt');

        $this->repeatRequiredOptions['card'] = new CreditCard(array('cvv' => '123'));

        $request = $this->gateway->repeatPayment($this->repeatRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testPreAuthCapture()
    {
        $this->setMockHttpResponse('PreAuthCaptureSuccess.txt');

        $request = $this->gateway->preAuthCapture($this->preAuthCaptureRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testPreAuthVoid()
    {
        $this->setMockHttpResponse('PreAuthVoidSuccess.txt');

        $request = $this->gateway->preAuthVoid($this->preAuthVoidRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSubscriptionCancellation()
    {
        $this->setMockHttpResponse('SubscriptionCancellationSuccess.txt');

        $request = $this->gateway->subscriptionCancellation($this->subscriptionCancellationRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testTransactionConfirm()
    {
        $this->setMockHttpResponse('TransactionConfirmSuccess.txt');

        $request = $this->gateway->transactionConfirm($this->transactionConfirmRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(is_array($response->getResponse()));

        $originalResponse = $response->getResponse();
        $this->assertEquals('12345678', $originalResponse['intTransID']);

        $this->assertNull($response->getMessage());
    }

    public function testFraudGuardCheck()
    {
        //@todo FraudGuardCheckSuccess is just a copy of PaymentSuccess. Update it to be a as-live response.
        $this->setMockHttpResponse('FraudGuardCheckSuccess.txt');

        $request = $this->gateway->fraudGuardCheck($this->fraudGuardCheckRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function test3DSecureAuthResume()
    {
        $this->setMockHttpResponse('PaymentSuccess.txt');

        $request = $this->gateway->s3DAuthorisationResume($this->s3DAuthorisationResumeRequiredOptions);
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }
} 