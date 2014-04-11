<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * SubscriptionCancellationRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class SubscriptionCancellationRequest extends AbstractRequest
{
    protected $transType = 'CANCEL';
    protected $requiredParams = array('instId', 'scheduleId');

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

        $data['intScheduleID'] = $this->getScheduleId();

        $data = $this->cleanNullData($data);

        return $data;
    }

    /**
     * getScheduleId.
     *
     *
     * @return int
     */
    public function getScheduleId()
    {
        return $this->getParameter('scheduleId');
    }

    /**
     * setScheduleId.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setScheduleId($value)
    {
        return $this->setParameter('scheduleId', $value);
    }
}
