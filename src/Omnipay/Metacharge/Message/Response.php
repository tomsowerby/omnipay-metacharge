<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Response.
 *
 * Created on: 01/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class Response extends AbstractResponse
{
    /**
     * @param RequestInterface $request
     * @param $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    /**
     * isSuccessful.
     *
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return array_key_exists('intStatus', $this->data) && ($this->data['intStatus'] == 1);
    }

    /**
     * getTransactionId.
     *
     *
     * @return int|null
     */
    public function getTransactionId()
    {
        return array_key_exists('intTransID', $this->data) ? $this->data['intTransID'] : null;
    }

    /**
     * getTime.
     *
     *
     * @return int
     */
    public function getTime()
    {
        return $this->data['intTime'];
    }

    /**
     * getMessage.
     *
     *
     * @return string|null
     */
    public function getMessage()
    {
        return array_key_exists('strMessage', $this->data) ? $this->data['strMessage'] : null;
    }
}
