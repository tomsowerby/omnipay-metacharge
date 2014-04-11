<?php

namespace Omnipay\Metacharge\Message;

/**
 * AbstractCardResponse.php
 *
 * Created on: 09/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class AbstractCardResponse extends Response
{
    /**
     * getFraudScore.
     *
     * Likelihood of the transaction being fraudulent. A value between 0.000 and 10.000, 0.000 being
     * the most unlikely and 10.000 being the most likely.
     *
     * @return float|null
     */
    public function getFraudScore()
    {
        return array_key_exists('fltFraudScore', $this->data) ? $this->data['fltFraudScore'] : null;
    }

    /**
     * getOriginalAmount.
     *
     * Included to reflect the original amount of the Payment Request, in case currency conversion was
     * performed during authorisation.
     *
     * @return float|null
     */
    public function getOriginalAmount()
    {
        return array_key_exists('fltOriginalAmount', $this->data) ? $this->data['fltOriginalAmount'] : null;
    }

    /**
     * getOriginalCurrency.
     *
     * Included to reflect the original currency of the Payment Request, in case currency conversion was
     * performed during authorisation.
     *
     * @return string|null
     */
    public function getOriginalCurrency()
    {
        return array_key_exists('strOriginalCurrency', $this->data) ? $this->data['strOriginalCurrency'] : null;
    }

    /**
     * getCountryIP.
     *
     * The result of checking the purchaser’s country as determined from their IP address against the
     * country supplied as part of the billing address. This field will be omitted if the check was not
     * performed. performed Values: 0=check failed, 1=check passed.
     *
     * @return bool|null
     */
    public function getCountryIP()
    {
        return array_key_exists('intCountryIP', $this->data) ? $this->data['intCountryIP'] : null;
    }
}
