<?php

namespace Omnipay\Metacharge\Message;


/**
 * FraudGuardCheckRequest.php
 *
 * Created on: 09/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class FraudGuardCheckRequest extends AbstractCustomerRequest
{
    protected $transType = 'NONAUTH';
    protected $requiredParams = array('instId', 'cartId', 'description', 'card');
    //no default used on this one, check number
    protected $requiredCardParams = array('email', 'billingCity', 'billingCountry');

    /**
     * getData.
     *
     *
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @return array
     */
    public function getData()
    {
        $this->getCard()->setParentValidate(false);

        $data = parent::getData();

        $data['strCardNumber'] = $this->getCard()->getNumber();

        if (!is_null($this->getCard()->getBrand())) {
            $data['strCardType'] = strtoupper($this->getCard()->getBrand());
        }

        $data['strPaymentDetail'] = $this->getPaymentDetail();
        $data['strPaymentType'] = $this->getPaymentType();

        //User
        $data['strUserIP'] = $this->getClientIp();

        $data = $this->cleanNullData($data);

        return $data;
    }

    /**
     * createResponse.
     *
     * @param $data
     *
     * @return FraudGuardCheckResponse|Response
     */
    protected function createResponse($data)
    {
        return $this->response = new FraudGuardCheckResponse($this, $data);
    }

    /**
     * getPaymentDetail.
     *
     *
     * @return string
     */
    public function getPaymentDetail()
    {
        return $this->getParameter('paymentDetail');
    }

    /**
     * setPaymentDetail.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPaymentDetail($value)
    {
        return $this->setParameter('paymentDetail', $value);
    }

    /**
     * getPaymentType.
     *
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    /**
     * setPaymentType.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }
}
