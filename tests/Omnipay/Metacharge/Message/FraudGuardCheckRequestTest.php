<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Metacharge\Gateway;
use Omnipay\Metacharge\CreditCard;

class FraudGuardCheckRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setInstId(1234);
        $this->gateway = $gateway;

        $this->fraudGuardCheckOptions = array(
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
    }

    public function testPaymentTypeAndDetailCanBeSet()
    {
        $this->fraudGuardCheckOptions['paymentType'] = 'GIRO';
        $this->fraudGuardCheckOptions['paymentDetail'] = '123456';

        $request = $this->gateway->fraudGuardCheck($this->fraudGuardCheckOptions);

        // No number set, strCardType should be null
        $data = $request->getData();
        $this->assertEquals('GIRO', $data['strPaymentType']);
        $this->assertEquals('123456', $data['strPaymentDetail']);
    }


    public function testBrandNotSetWhenNoNumber()
    {
        $request = $this->gateway->fraudGuardCheck($this->fraudGuardCheckOptions);

        // No number set, strCardType should be null
        $data = $request->getData();
        $this->assertArrayNotHasKey('strCardType', $data);
    }

    public function testBrandSettingAutomaticWithKnownNumber()
    {
        // 4111111111111111, recognised automatically as VISA
        $this->fraudGuardCheckOptions['card']->setNumber('4111111111111111');

        $request = $this->gateway->fraudGuardCheck($this->fraudGuardCheckOptions);

        // No number set, strCardType should be null
        $data = $request->getData();
        $this->assertEquals('VISA', $data['strCardType']);
    }

    /**
     * testBrandThrowsExceptionSetWithUnknownNumber.
     *
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function testBrandThrowsExceptionSetWithUnknownNumber()
    {
        // 1234123412341234, Not recognised by Omnipay, allowed as VISA by Metacharge
        $this->fraudGuardCheckOptions['card']->setNumber('1234123412341234');

        $request = $this->gateway->fraudGuardCheck($this->fraudGuardCheckOptions);

        // No number set, strCardType should be null
        $request->getData();
    }
}
