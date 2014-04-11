<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Metacharge\CreditCard;

/**
 * Class AbstractRequest.
 *
 * Created on: 01/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    const API_VERSION = 1.4;

    /**
     * endpoint.
     *
     * @var string
     */
    protected $endpoint = 'https://secure.metacharge.com/mcpe/corporate';

    /**
     * cleanNullData.
     * Removes any null data from a request to keep requests short and sweet.
     *
     * @param array $data
     *
     * @return array
     */
    protected function cleanNullData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * setCard.
     * Replaces default set card to change card type, allowing more validation.
     *
     * @param $value
     *
     * @return $this
     */
    public function setCard($value)
    {
        //Handle when a Common Credit Card type is used.
        if ($value instanceof \Omnipay\Common\CreditCard) {
            $value = $value->getParameters();
        }

        if ($value && !$value instanceof CreditCard) {
            $value = new CreditCard($value);
        }

        return $this->setParameter('card', $value);
    }

    /**
     * getInstId.
     *
     *
     * @return int
     */
    public function getInstId()
    {
        return $this->getParameter('instId');
    }

    /**
     * setInstId.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setInstId($value)
    {
        return $this->setParameter('instId', $value);
    }

    /**
     * getSharedKey.
     *
     *
     * @return string
     */
    public function getSharedKey()
    {
        return $this->getParameter('sharedKey');
    }

    /**
     * setSharedKey.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setSharedKey($value)
    {
        return $this->setParameter('sharedKey', $value);
    }

    /**
     * get3DSecureResponseUrl.
     *
     *
     * @return string
     */
    public function get3DSecureResponseUrl()
    {
        return $this->getParameter('3DSecureResponseUrl');
    }

    /**
     * set3DSecureResponseUrl.
     *
     * @param string $value
     *
     * @return $this
     */
    public function set3DSecureResponseUrl($value)
    {
        return $this->setParameter('3DSecureResponseUrl', $value);
    }

    //Payment, Payout, FraudGuard
    /**
     * getAccountId.
     *
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * setAccountId.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    // Payment, Payout, Transaction Confirm, FraudGuard
    /**
     * getCartId.
     *
     *
     * @return string
     */
    public function getCartId()
    {
        return $this->getParameter('cartId');
    }

    /**
     * setCartId.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setCartId($value)
    {
        return $this->setParameter('cartId', $value);
    }

    // Refund, Repeat, PreAuth(both), S3D Auth Resume
    /**
     * getSecurityToken.
     *
     *
     * @return string
     */
    public function getSecurityToken()
    {
        return $this->getParameter('securityToken');
    }

    /**
     * setSecurityToken.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setSecurityToken($value)
    {
        return $this->setParameter('securityToken', $value);
    }

    /**
     * sendData.
     *
     * @param array $data
     *
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     */
    public function sendData($data)
    {
        $url = $this->endpoint;
        $httpResponse = $this->httpClient->post($url, array(), $data)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    /**
     * getBaseData.
     *
     * @param string $transType
     *
     * @return array
     */
    protected function getBaseData($transType)
    {
        $data = array();

        $data['intInstID'] = $this->getInstId();
        $data['fltAPIVersion'] = static::API_VERSION;
        $data['strTransType'] = strtoupper($transType);
        $data['intTestMode'] = $this->getTestMode();

        return $data;
    }

    /**
     * createResponse.
     *
     * @param $data
     *
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
