<?php

namespace Omnipay\Metacharge\Message;


/**
 * PreAuthVoidRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class PreAuthVoidRequest extends AbstractFollowupRequest
{
    protected $transType = 'VOID';
    protected $requiredParams = array('instId', 'transactionId', 'securityToken');
}
