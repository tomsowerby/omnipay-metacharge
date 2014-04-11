<?php

namespace Omnipay\Metacharge\Message;

/**
 * FraudGuardCheckResponse.php
 *
 * Created on: 09/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class FraudGuardCheckResponse extends AbstractCardResponse
{
    /**
     * getAdvisory.
     *
     * The advised outcome of the transaction as dictated by your configuration of FraudGuard.
     * Possible values are 0=suggest decline, 1=suggest approve, 2=suggest defer for review.
     *
     * @return int|null
     */
    public function getAdvisory()
    {
        return array_key_exists('intAdvisory', $this->data) ? $this->data['intAdvisory'] : null;
    }
}
