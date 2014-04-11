<?php

namespace Omnipay\Metacharge\Message;

/**
 * AbstractCardRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
abstract class AbstractCardRequest extends AbstractCustomerRequest
{
    /**
     * getData.
     *
     *
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();

        $data['strCardNumber'] = $this->getCard()->getNumber();
        if (!is_null($this->getCard()->getStartMonth()) && !is_null($this->getCard()->getStartYear())) {
            $data['strStartDate'] = $this->getCard()->getStartDate('my');
        }
        if (!is_null($this->getCard()->getExpiryMonth()) && !is_null($this->getCard()->getExpiryYear())) {
            $data['strExpiryDate'] = $this->getCard()->getExpiryDate('my');
        }
        $data['intCV2'] = $this->getCard()->getCvv();
        $data['strIssueNo'] = $this->getCard()->getIssueNumber();

        if (!is_null($this->getCard()->getBrand())) {
            $data['strCardType'] = strtoupper($this->getCard()->getBrand());
        }

        /*
         * An additional authentication of the transaction request; an MD5
         * Page 6 of 23hash of a string created as follows:
         * intInstID + strCardNumber + fltAmount [to 2 decimal places] + strCurrency + your shared key.
         * See section 3.3 for more details on implementing a digest.
         */
        if (!is_null($this->getSharedKey())) {
            $data['strDigest'] = md5(
                $this->getInstId() .
                $this->getCard()->getNumber() .
                $this->getAmount() .
                strtoupper($this->getCurrency()) .
                $this->getSharedKey()
            );
        }

        $data = $this->cleanNullData($data);

        return $data;
    }
}
