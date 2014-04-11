<?php

namespace Omnipay\Metacharge\Message;

/**
 * RefundRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class RefundRequest extends AbstractFollowupRequest
{
    protected $transType = 'REFUND';
    protected $requiredParams = array('instId', 'transactionId', 'securityToken', 'amount');
}
