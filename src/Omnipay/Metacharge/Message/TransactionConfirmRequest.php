<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * TransactionConfirmRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class TransactionConfirmRequest extends AbstractRequest
{
    protected $transType = 'CONFIRM';
    protected $requiredParams = array('instId', 'cartId');

    /**
     * getData.
     *
     *
     * @throws InvalidRequestException
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData($this->transType);

        call_user_func_array(array($this, 'validate'), $this->requiredParams);

        $data['strCartID'] = $this->getCartId();
        $data['strConfirmType'] = $this->getConfirmType();

        $data = $this->cleanNullData($data);

        return $data;
    }

    /**
     * createResponse.
     *
     * @param $data
     *
     * @return Response|TransactionConfirmResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new TransactionConfirmResponse($this, $data);
    }

    /**
     * getConfirmType.
     *
     *
     * @return string
     */
    public function getConfirmType()
    {
        return $this->getParameter('confirmType');
    }

    /**
     * setConfirmType.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setConfirmType($value)
    {
        return $this->setParameter('confirmType', $value);
    }
}
