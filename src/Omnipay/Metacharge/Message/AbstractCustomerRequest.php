<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;

/**
 * AbstractCustomerRequest.php
 *
 * Created on: 09/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
abstract class AbstractCustomerRequest extends AbstractRequest
{
    /**
     * transType.
     *
     * @var string
     */
    protected $transType;
    /**
     * requiredParams.
     *
     * @var array
     */
    protected $requiredParams = array();
    /**
     * requiredCardParams.
     *
     * @var array
     */
    protected $requiredCardParams = array();

    /**
     * getData.
     *
     *
     * @throws InvalidCreditCardException
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData($this->transType);

        call_user_func_array(array($this, 'validate'), $this->requiredParams);

        call_user_func_array(array($this->getCard(), 'validate'), $this->requiredCardParams);

        //Transaction
        $data['strCartID'] = $this->getCartId();
        $data['intAccountID'] = $this->getAccountId();
        $data['strDesc'] = $this->getDescription();
        $data['fltAmount'] = $this->getAmount();
        $data['strCurrency'] = $this->getCurrency();
        $data['intReference'] = $this->getTransactionReference();

        //Card
        $data['strCardHolder'] = $this->getCard()->getName();
        $data['strAddress'] = $this->getCard()->getAddress1();
        if (!is_null($this->getCard()->getAddress2())) {
            if (!is_null($this->getCard()->getAddress1())) {
                $data['strAddress'] .= ', ';
            }
            $data['strAddress'] .= $this->getCard()->getAddress2();
        }
        $data['strCity'] = $this->getCard()->getCity();
        $data['strState'] = $this->getCard()->getState();
        $data['strPostcode'] = $this->getCard()->getPostcode();
        $data['strCountry'] = $this->getCard()->getCountry();

        $data['strTel'] = $this->getCard()->getPhone();
        $data['strFax'] = $this->getCard()->getFax();
        $data['strEmail'] = $this->getCard()->getEmail();

        $data = $this->cleanNullData($data);

        return $data;
    }
}
