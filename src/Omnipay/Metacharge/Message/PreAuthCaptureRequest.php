<?php

namespace Omnipay\Metacharge\Message;

/**
 * PreAuthCaptureRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class PreAuthCaptureRequest extends AbstractFollowupRequest
{
    protected $transType = 'CAPTURE';
    protected $requiredParams = array('instId', 'transactionId', 'securityToken');
}
