<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Metacharge\Gateway;
use Omnipay\Metacharge\CreditCard;

class PaymentRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setInstId(1234);
        $gateway->set3DSecureResponseUrl('http://www.someurl.com');
        $this->gateway = $gateway;

        $this->paymentOptions = array(
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
    }


    public function testAuthModeCanBeSet()
    {
        $this->paymentOptions['authMode'] = 2;

        $request = $this->gateway->purchase($this->paymentOptions);

        $data = $request->getData();
        $this->assertEquals(2, $data['intAuthMode']);
    }


    public function testExpiryDateSetCorrectly()
    {
        $request = $this->gateway->purchase($this->paymentOptions);

        $data = $request->getData();

        //Response is upset if this isn't exactly 4
        $this->assertEquals(4, strlen($data['strExpiryDate']));
        $this->assertEquals("0216", $data['strExpiryDate']);
    }


    public function testStartDateSetCorrectly()
    {
        $this->paymentOptions['card']->setStartMonth('01');
        $this->paymentOptions['card']->setStartYear('2002');

        $request = $this->gateway->purchase($this->paymentOptions);

        $data = $request->getData();

        //Response is upset if this isn't exactly 4
        $this->assertEquals(4, strlen($data['strStartDate']));
        $this->assertEquals('0102', $data['strStartDate']);
    }


    public function testFulfillmentDateSetCorrectly()
    {
        $request = $this->gateway->purchase($this->paymentOptions);
        $request->setFulfillmentDay('03');
        $request->setFulfillmentMonth('04');
        $request->setFulfillmentYear('2015');

        $data = $request->getData();

        $this->assertEquals(10, strlen($data['datFulfillment']));
        $this->assertEquals('03/04/2015', $data['datFulfillment']);
    }


    public function testScheduleSetCorrectly()
    {
        $request = $this->gateway->purchase($this->paymentOptions);
        $request->setScheduleAmount(15.99);
        $request->setSchedulePeriod('123D');
        $request->setRecurs(1);
        $request->setCancelAfter(12);

        $data = $request->getData();

        $this->assertEquals(15.99, $data['fltSchAmount']);
        $this->assertEquals('123D', $data['strSchPeriod']);
        $this->assertEquals(1, $data['intRecurs']);
        $this->assertEquals(12, $data['intCancelAfter']);
    }


    public function testStreetCanBeSet()
    {
        $this->paymentOptions['card']->setAddress1('123 Street');
        $this->paymentOptions['card']->setAddress2('Village');

        $request = $this->gateway->purchase($this->paymentOptions);

        $data = $request->getData();
        $this->assertEquals('123 Street, Village', $data['strAddress']);
    }

    public function testAccountIdCanBeSetViaGateway()
    {
        $this->gateway->setAccountId(444444);
        $request = $this->gateway->purchase($this->paymentOptions);

        $data = $request->getData();
        $this->assertEquals(444444, $data['intAccountID']);
    }

    public function testAccountIdCanBeSetViaRequest()
    {
        $this->paymentOptions['accountId'] = 333333;
        $this->assertNull($this->gateway->getAccountId());

        $request = $this->gateway->purchase($this->paymentOptions);

        $data = $request->getData();
        $this->assertEquals(333333, $data['intAccountID']);
    }

    public function testBrandSettingAutomaticWithKnownNumber()
    {
        $request = $this->gateway->purchase($this->paymentOptions);

        // No number set, strCardType should be null
        $data = $request->getData();
        $this->assertEquals('VISA', $data['strCardType']);
    }

    /**
     * testCardValidationExceptionWith3DSecureNumberAndNotInTestMode.
     *
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function testCardValidationExceptionWith3DSecureNumberAndNotInTestMode()
    {
        //Set the 3DS test number
        $this->paymentOptions['card']->setNumber(1234123412341234);

        $request = $this->gateway->purchase($this->paymentOptions);
        $request->getData();
    }

    public function testCardValidationWith3DSecureNumberAndInTestMode()
    {
        //Set the 3DS test number
        $this->paymentOptions['card']->setNumber(1234123412341234);

        $this->gateway->setTestMode(true);

        $request = $this->gateway->purchase($this->paymentOptions);
        $data = $request->getData();

        $this->assertSame('1234123412341234', $data['strCardNumber']);
        $this->assertSame('VISA', $data['strCardType']);
    }

    /**
     * testBrandThrowsExceptionSetWithUnknownNumber.
     *
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function testBrandThrowsExceptionSetWithUnknownNumber()
    {
        // 1234123412341234, Not recognised by Omnipay, allowed as VISA by Metacharge
        $this->paymentOptions['card']->setNumber('1234123412341234');

        $request = $this->gateway->purchase($this->paymentOptions);

        // No number set, strCardType should be null
        $request->getData();
    }
}
