<?php

namespace Omnipay\Metacharge\Message;

/**
 * PayoutRequest.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class PayoutRequest extends AbstractCardRequest
{
    protected $transType = 'PAYOUT';
    protected $requiredParams = array('instId', 'cartId', 'description', 'amount', 'currency', 'card');
    //number and expiry handled by default
    protected $requiredCardParams = array('billingFirstName', 'billingLastName', 'email');
}
