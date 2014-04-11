<?php

namespace Omnipay\Metacharge\Message;

/**
 * RepeatPaymentRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class RepeatPaymentRequest extends AbstractFollowupRequest
{
    protected $transType = 'REPEAT';
    protected $requiredParams = array('instId', 'transactionId', 'securityToken', 'amount');

    /**
     * getData.
     *
     *
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();

        if (!is_null($this->getCard())) {
            $data['intCV2'] = $this->getCard()->getCvv();
        }
        $data['strUserIP'] = $this->getClientIp();

        $data = $this->cleanNullData($data);

        return $data;
    }
}
