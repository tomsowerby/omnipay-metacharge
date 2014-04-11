<?php

namespace Omnipay\Metacharge\Message;


/**
 * AbstractFollowupRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
abstract class AbstractFollowupRequest extends AbstractRequest
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
     * getData.
     *
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData($this->transType);

        call_user_func_array(array($this, 'validate'), $this->requiredParams);

        $data['intTransID'] = $this->getTransactionId();
        $data['strSecurityToken'] = $this->getSecurityToken();
        $data['fltAmount'] = $this->getAmount();
        $data['strDesc'] = $this->getDescription();
        $data['intReference'] = $this->getTransactionReference();

        $data = $this->cleanNullData($data);

        return $data;
    }
}
