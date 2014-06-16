<?php
/**
 * CreditCardTest.php
 *
 * Created on: 10/04/14
 * 
 * @package 
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */

namespace Omnipay\Metacharge;

use Omnipay\Tests\TestCase;
use Omnipay\Metacharge\CreditCard;

class CreditCardTest extends TestCase
{
    /**
     * testExceptionWhenNotValid.
     *
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function testExceptionWhenNotValid()
    {
        $card = new CreditCard();
        $card->validate('not-existing');
    }

    /**
     * testExceptionWhenInvalidNumberExcludingParent.
     *
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function testExceptionWhenInvalidNumberExcludingParent()
    {
        $card = new CreditCard();
        $card->setNumber(1);
        $card->setParentValidate(false);
        $card->validate();
    }

    public function testNoExceptionWhenInvalidNumberExcludingParentAndIgnoreNumber()
    {
        $card = new CreditCard();
        $card->setNumber(1);
        $card->setParentValidate(false);
        $card->setIgnoreNumber(true);
        $card->validate();
    }

    public function testCanSetFax()
    {
        $gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setInstId(1234);
        $gateway->set3DSecureResponseUrl('http://www.someurl.com');

        $card = new CreditCard(array(
            'firstName' => 'Joe',
            'lastName' => 'Bloggs',
            'postcode' => 'BA12BU',
            'email' => 'test@paypoint.net',
            'number' => '4111111111111111',
            'expiryMonth' => '02',
            'expiryYear' => '2016',
            'cvv' => '123',
            'fax' => '1234512345'
        ));

        $this->assertEquals(1234512345, $card->getBillingFax());
        $this->assertEquals(1234512345, $card->getShippingFax());

        $card->setBillingFax(543543);
        $card->setShippingFax(987987);

        $paymentOptions = array(
            'cartId' => 4567,
            'description' => 'A description',
            'amount' => '10.00',
            'currency' => 'GBP',
            'card' => $card,
        );

        $request = $gateway->purchase($paymentOptions);

        $data = $request->getData();
        $this->assertEquals(543543, $data['strFax']);
        $this->assertEquals(543543, $card->getFax());
        $this->assertEquals(543543, $card->getBillingFax());
        $this->assertEquals(987987, $card->getShippingFax());

    }
} 